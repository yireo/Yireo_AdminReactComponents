<?php
/**
 * AdminReactComponents plugin for Magento
 *
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2023 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

declare(strict_types=1);

namespace Yireo\AdminReactComponents\Model;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Customer\Model\Customer as CustomerModel;
use Magento\Store\Api\WebsiteRepositoryInterface;

class CustomerData
{
    private GroupRepositoryInterface $customerGroupRepository;
    private WebsiteRepositoryInterface $websiteRepository;

    public function __construct(
        GroupRepositoryInterface $customerGroupRepository,
        WebsiteRepositoryInterface $websiteRepository
    ) {
        $this->customerGroupRepository = $customerGroupRepository;
        $this->websiteRepository = $websiteRepository;
    }

    public function get(CustomerInterface $customer)
    {

        /** @var $customer CustomerModel */
        $customerGroup = $this->customerGroupRepository->getById($customer->getGroupId());
        $website = $this->websiteRepository->getById($customer->getWebsiteId());

        return [
            'id' => $customer->getId(),
            'label' => $this->getCustomerLabel($customer),
            'name' => $this->getCustomerName($customer),
            'email' => $customer->getEmail(),
            'group_id' => $customerGroup->getId(),
            'group_label' => $customerGroup->getCode(),
            'website_id' => $website->getId(),
            'website_label' => $website->getName(),
        ];
    }

    /**
     * @param CustomerInterface $customer
     *
     * @return string
     */
    private function getCustomerName(CustomerInterface $customer): string
    {
        return implode(' ', [
            $customer->getFirstname(),
            $customer->getMiddlename(),
            $customer->getLastname()
        ]);
    }

    private function getCustomerLabel(CustomerInterface $customer): string
    {
        return $this->getCustomerName($customer) . ' (' . $customer->getEmail() . ')';
    }
}
