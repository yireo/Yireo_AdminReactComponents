<?php
/**
 * AdminReactComponents plugin for Magento
 *
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2023 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

declare(strict_types=1);

namespace Yireo\AdminReactComponents\Controller\Adminhtml\Ajax;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Api\SortOrderBuilderFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\Controller\Result\JsonFactory;

abstract class AbstractSearch extends Action implements HttpGetActionInterface
{
    private const MAX_QUERY_LENGTH = 100;
    private const MAX_SEARCH_WORDS = 5;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var HttpRequest
     */
    protected $request;

    /**
     * @var SearchCriteriaBuilderFactory
     */
    protected $searchCriteriaBuilderFactory;

    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;
    /**
     * @var SortOrderBuilderFactory
     */
    private $sortOrderBuilderFactory;

    /**
     * @param Context $context
     * @param HttpRequest $request
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param FilterBuilder $filterBuilder
     * @param JsonFactory $resultJsonFactory
     * @param SortOrderBuilderFactory $sortOrderBuilderFactory
     */
    public function __construct(
        Context $context,
        HttpRequest $request,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        FilterBuilder $filterBuilder,
        JsonFactory $resultJsonFactory,
        SortOrderBuilderFactory $sortOrderBuilderFactory
    ) {
        parent::__construct($context);

        $this->request = $request;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->filterBuilder = $filterBuilder;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->sortOrderBuilderFactory = $sortOrderBuilderFactory;
    }

    /**
     * Mapping of allowed sort fields (as used in the frontend) to the actual database fields
     *
     * @return string[]
     */
    abstract protected function getSortFieldMapping(): array;

    /**
     * @return string
     */
    protected function getSearchQuery(): string
    {
        $searchQuery = trim((string)$this->request->getParam('search'), " \f\n\r\t\v\x00");
        return mb_substr($searchQuery, 0, self::MAX_QUERY_LENGTH);
    }

    /**
     * @return string
     */
    protected function getSortField(): string
    {
        $sortField = (string)$this->request->getParam('sortField');
        $sortFieldMapping = $this->getSortFieldMapping();
        return $sortFieldMapping[$sortField] ?? '';
    }

    /**
     * @return string
     */
    protected function getSortDirection(): string
    {
        $sortDirection = strtoupper((string)$this->request->getParam('sortDirection'));
        return $sortDirection === SortOrder::SORT_DESC ? SortOrder::SORT_DESC : SortOrder::SORT_ASC;
    }

    /**
     * @param array $searchFields
     * @return SearchCriteria
     */
    protected function getSearchCriteria(array $searchFields): SearchCriteria
    {
        $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
        $searchCriteriaBuilder->setCurrentPage(0);
        $searchCriteriaBuilder->setPageSize(10);

        $filters = $this->getSearchFilters($searchFields);
        if (!empty($filters)) {
            $searchCriteriaBuilder->addFilters($filters);
        }

        $sortOrder = $this->getSortOrder();
        if ($sortOrder instanceof SortOrder) {
            $searchCriteriaBuilder->setSortOrders([$sortOrder]);
        }

        return $searchCriteriaBuilder->create();
    }

    /**
     * @return string[]
     */
    protected function getSearchWords(): array
    {
        $searchWords = preg_split('/\s+/u', $this->getSearchQuery(), -1, PREG_SPLIT_NO_EMPTY);
        if (!is_array($searchWords)) {
            return [];
        }

        return array_slice(array_values(array_unique($searchWords)), 0, self::MAX_SEARCH_WORDS);
    }

    /**
     * @param string $value
     * @return string
     */
    protected function escapeLikeValue(string $value): string
    {
        return addcslashes($value, '\\%_');
    }

    /**
     * @param array $searchFields
     * @return array
     */
    private function getSearchFilters(array $searchFields = []): array
    {
        $filters = [];

        foreach ($searchFields as $field) {
            foreach ($this->getSearchWords() as $searchWord) {
                $filters[] = $this->filterBuilder
                    ->setField($field)
                    ->setConditionType('like')
                    ->setValue('%' . $this->escapeLikeValue($searchWord) . '%')
                    ->create();
            }
        }

        return $filters;
    }

    /**
     * @return SortOrder|null
     */
    private function getSortOrder(): ?SortOrder
    {
        $sortField = $this->getSortField();
        if (empty($sortField)) {
            return null;
        }

        /** @var SortOrderBuilder $sortOrderBuilder */
        $sortOrderBuilder = $this->sortOrderBuilderFactory->create();
        $sortOrderBuilder
            ->setField($sortField)
            ->setDirection($this->getSortDirection());

        return $sortOrderBuilder->create();
    }
}
