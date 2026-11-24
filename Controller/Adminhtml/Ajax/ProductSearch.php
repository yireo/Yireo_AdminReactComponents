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

use Magento\Backend\App\Action\Context;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\SortOrderBuilderFactory;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\JsonFactory;
use Psr\Log\LoggerInterface;

class ProductSearch extends AbstractSearch
{
    public const ADMIN_RESOURCE = 'Magento_Catalog::products';

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * ProductSearch constructor.
     * @param Context $context
     * @param Http $request
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param FilterBuilder $filterBuilder
     * @param JsonFactory $resultJsonFactory
     * @param SortOrderBuilderFactory $sortOrderBuilderFactory
     * @param LoggerInterface $logger
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Context $context,
        Http $request,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        FilterBuilder $filterBuilder,
        JsonFactory $resultJsonFactory,
        SortOrderBuilderFactory $sortOrderBuilderFactory,
        LoggerInterface $logger,
        ProductRepositoryInterface $productRepository
    ) {
        parent::__construct(
            $context,
            $request,
            $searchCriteriaBuilderFactory,
            $filterBuilder,
            $resultJsonFactory,
            $sortOrderBuilderFactory,
            $logger
        );

        $this->productRepository = $productRepository;
    }

    /**
     * @return array
     */
    protected function getSearchResults(): array
    {
        $productData = [];
        $searchFields = ['name', 'sku'];
        $searchResults = $this->productRepository->getList($this->getSearchCriteria($searchFields));

        foreach ($searchResults->getItems() as $product) {
            /** @var $product ProductInterface */
            $productData[] = [
                'id' => $product->getId(),
                'sku' => $product->getSku(),
                'name' => $product->getName()
            ];
        }

        return $productData;
    }

    /**
     * @return string[]
     */
    protected function getSortFieldMapping(): array
    {
        return [
            'id' => 'entity_id',
            'name' => 'name',
            'sku' => 'sku',
        ];
    }
}
