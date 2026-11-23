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
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\SortOrderBuilderFactory;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class OrderSearch extends AbstractSearch
{
    public const ADMIN_RESOURCE = 'Magento_Sales::actions_view';

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * OrderSearch constructor.
     * @param Context $context
     * @param Http $request
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param FilterBuilder $filterBuilder
     * @param JsonFactory $resultJsonFactory
     * @param SortOrderBuilderFactory $sortOrderBuilderFactory
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        Context $context,
        Http $request,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        FilterBuilder $filterBuilder,
        JsonFactory $resultJsonFactory,
        SortOrderBuilderFactory $sortOrderBuilderFactory,
        OrderRepositoryInterface $orderRepository
    ) {
        parent::__construct(
            $context,
            $request,
            $searchCriteriaBuilderFactory,
            $filterBuilder,
            $resultJsonFactory,
            $sortOrderBuilderFactory
        );

        $this->orderRepository = $orderRepository;
    }

    /**
     * Index action
     *
     * @return Json
     */
    public function execute(): Json
    {
        $orderData = [];
        $searchFields = ['increment_id', 'customer_email'];
        try {
            $searchResults = $this->orderRepository->getList($this->getSearchCriteria($searchFields));
        } catch (LocalizedException $e) {
            return $this->resultJsonFactory->create()->setData([]);
        }

        foreach ($searchResults->getItems() as $order) {
            /** @var $order OrderInterface */
            $orderData[] = [
                'id' => $order->getEntityId(),
                'increment_id' => $order->getIncrementId(),
                'customer_email' => $order->getCustomerEmail(),
                'created_at' => $order->getCreatedAt(),
            ];
        }

        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($orderData);
    }

    /**
     * @return string[]
     */
    protected function getSortFieldMapping(): array
    {
        return [
            'id' => 'entity_id',
            'increment_id' => 'increment_id',
            'customer_email' => 'customer_email',
            'created_at' => 'created_at',
        ];
    }
}
