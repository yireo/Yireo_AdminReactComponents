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

class CustomerLabel extends AbstractLabel
{
    const ADMIN_RESOURCE = 'Yireo_AdminReactComponents::index';

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @param Context $context
     * @param Http $request
     * @param JsonFactory $resultJsonFactory
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        Context $context,
        Http $request,
        JsonFactory $resultJsonFactory,
        CustomerRepositoryInterface $customerRepository
    ) {
        parent::__construct($context, $request, $resultJsonFactory);
        $this->customerRepository = $customerRepository;
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    protected function getLabel(): string
    {
        $id = $this->getId();
        if (!$id > 0) {
            throw new NoSuchEntityException(__('Empty ID'));
        }

        $customer = $this->customerRepository->getById($id);
        return $customer->getFirstname() . ' ' . $customer->getLastname() . ' (' . $customer->getEmail() . ')';
    }

    /**
     * @return string
     */
    protected function getEmptyLabel(): string
    {
        return 'No customer data found';
    }
}
