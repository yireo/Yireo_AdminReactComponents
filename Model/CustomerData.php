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
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Api\WebsiteRepositoryInterface;

class CustomerData
{
    public function __construct(
        private readonly GroupRepositoryInterface $customerGroupRepository,
        private readonly WebsiteRepositoryInterface $websiteRepository
    ) {
    }

    /**
     * @param CustomerInterface $customer
     * @return array
     */
    public function get(CustomerInterface $customer): array
    {
        return [
            'id' => $customer->getId(),
            'label' => $this->getCustomerLabel($customer),
            'name' => $this->getCustomerName($customer),
            'email' => $customer->getEmail(),
            'group_id' => $customer->getGroupId(),
            'group_label' => $this->getGroupLabel((int)$customer->getGroupId()),
            'website_id' => $customer->getWebsiteId(),
            'website_label' => $this->getWebsiteLabel((int)$customer->getWebsiteId()),
        ];
    }

    private function getGroupLabel(int $groupId): string
    {
        try {
            return (string)$this->customerGroupRepository->getById($groupId)->getCode();
        } catch (LocalizedException $e) {
            return '';
        }
    }

    private function getWebsiteLabel(int $websiteId): string
    {
        try {
            return (string)$this->websiteRepository->getById($websiteId)->getName();
        } catch (LocalizedException $e) {
            return '';
        }
    }

    private function getCustomerName(CustomerInterface $customer): string
    {
        return implode(' ', array_filter([
            $customer->getFirstname(),
            $customer->getMiddlename(),
            $customer->getLastname(),
        ]));
    }

    private function getCustomerLabel(CustomerInterface $customer): string
    {
        return $this->getCustomerName($customer) . ' (' . $customer->getEmail() . ')';
    }
}
