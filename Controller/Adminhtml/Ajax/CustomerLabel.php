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
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Yireo\AdminReactComponents\Model\CustomerData;

class CustomerLabel extends AbstractLabel
{
    public const ADMIN_RESOURCE = 'Magento_Customer::manage';

    /**
     * @param Context $context
     * @param Http $request
     * @param JsonFactory $resultJsonFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerData $customerData
     */
    public function __construct(
        Context $context,
        Http $request,
        JsonFactory $resultJsonFactory,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly CustomerData $customerData
    ) {
        parent::__construct($context, $request, $resultJsonFactory);
    }

    /**
     * Index action
     *
     * @return Json
     */
    public function execute(): Json
    {
        $id = $this->getId();
        if ($id <= 0) {
            return $this->resultJsonFactory->create()->setData($this->getEmptyData());
        }

        try {
            $data = $this->customerData->get($this->customerRepository->getById($id));
        } catch (LocalizedException $e) {
            $data = $this->getEmptyData();
        }

        return $this->resultJsonFactory->create()->setData($data);
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    protected function getLabel(): string
    {
        $data = $this->customerData->get($this->customerRepository->getById($this->getId()));
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
