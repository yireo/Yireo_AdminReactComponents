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
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SortOrderBuilderFactory;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Yireo\AdminReactComponents\Model\CustomerData;

class CustomerSearch extends AbstractSearch
{
    public const ADMIN_RESOURCE = 'Magento_Customer::manage';

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var CustomerData
     */
    private $customerData;

    /**
     * @param Context $context
     * @param HttpRequest $request
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param FilterBuilder $filterBuilder
     * @param JsonFactory $resultJsonFactory
     * @param SortOrderBuilderFactory $sortOrderBuilderFactory
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        Context $context,
        HttpRequest $request,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        FilterBuilder $filterBuilder,
        JsonFactory $resultJsonFactory,
        SortOrderBuilderFactory $sortOrderBuilderFactory,
        CustomerRepositoryInterface $customerRepository,
        CustomerData $customerData
    ) {
        parent::__construct(
            $context,
            $request,
            $searchCriteriaBuilderFactory,
            $filterBuilder,
            $resultJsonFactory,
            $sortOrderBuilderFactory,
        );

        $this->customerRepository = $customerRepository;
        $this->customerData = $customerData;
    }

    /**
     * Index action
     *
     * @return Json
     */
    public function execute(): Json
    {
        $customerData = [];
        $searchFields = ['firstname', 'lastname', 'email'];
        try {
            $searchResults = $this->customerRepository->getList($this->getSearchCriteria($searchFields));
        } catch (LocalizedException $e) {
            return $this->resultJsonFactory->create()->setData([]);
        }

        foreach ($searchResults->getItems() as $customer) {
            $customerData[] = $this->customerData->get($customer);
        }

        return $this->resultJsonFactory->create()->setData($customerData);
    }

    /**
     * @return string[]
     */
    protected function getSortFieldMapping(): array
    {
        return [
            'id' => 'entity_id',
            'name' => 'firstname',
            'email' => 'email',
            'group_label' => 'group_id',
            'website_label' => 'website_id',
        ];
    }
}
