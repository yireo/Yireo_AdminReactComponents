<?php

declare(strict_types=1);

namespace Yireo\AdminReactComponents\Test\Integration\Controller\Adminhtml\Ajax;

use Magento\TestFramework\TestCase\AbstractBackendController;

/**
 * @magentoAppArea adminhtml
 */
class OrderSearchTest extends AbstractBackendController
{
    protected function setUp(): void
    {
        $this->resource = 'Magento_Sales::actions_view';
        $this->uri = 'backend/yireo_react/ajax/orderSearch';
        $this->httpMethod = 'GET';
        parent::setUp();
    }
}
