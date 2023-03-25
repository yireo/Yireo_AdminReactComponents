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
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Yireo\AdminReactComponents\Model\CustomerData;

class CustomerLabel implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Yireo_AdminReactComponents::index';
    private Http $request;
    private JsonFactory $resultJsonFactory;
    private CustomerRepositoryInterface $customerRepository;
    private CustomerData $customerData;

    /**
     * @param Context $context
     * @param Http $request
     * @param JsonFactory $resultJsonFactory
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        Http $request,
        JsonFactory $resultJsonFactory,
        CustomerRepositoryInterface $customerRepository,
        CustomerData $customerData
    ) {
        $this->request = $request;
        $this->resultJsonFactory = $resultJsonFactory;
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
        try {
            $id = (int)$this->request->getParam('id');
            $customer = $this->customerRepository->getById($id);
            $data = $this->customerData->get($customer);
        } catch (NoSuchEntityException $e) {
            $data = [
                'id' => 0,
                'label' => __('No customer found'),
            ];
        }

        return $this->resultJsonFactory->create()->setData($data);
    }
}
