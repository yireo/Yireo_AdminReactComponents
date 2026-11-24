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
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use Yireo\AdminReactComponents\Model\CustomerData;

class CustomerLabel extends AbstractLabel
{
    public const ADMIN_RESOURCE = 'Magento_Customer::manage';

    /**
     * @param Context $context
     * @param Http $request
     * @param JsonFactory $resultJsonFactory
     * @param LoggerInterface $logger
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerData $customerData
     */
    public function __construct(
        Context $context,
        Http $request,
        JsonFactory $resultJsonFactory,
        LoggerInterface $logger,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly CustomerData $customerData
    ) {
        parent::__construct($context, $request, $resultJsonFactory, $logger);
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    protected function getLabel(): string
    {
        try {
            $data = $this->customerData->get($this->customerRepository->getById($this->getId()));
        } catch (LocalizedException $e) {
            throw new NoSuchEntityException(__('Customer not found'));
        }

        return (string)$data['label'];
    }

    /**
     * @return string
     */
    protected function getEmptyLabel(): string
    {
        return 'No customer found';
    }
}
