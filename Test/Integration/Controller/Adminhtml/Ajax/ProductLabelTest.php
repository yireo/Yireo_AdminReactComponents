<?php

declare(strict_types=1);

namespace Yireo\AdminReactComponents\Test\Integration\Controller\Adminhtml\Ajax;

use Magento\TestFramework\TestCase\AbstractBackendController;

/**
 * @magentoAppArea adminhtml
 */
class ProductLabelTest extends AbstractBackendController
{
    protected function setUp(): void
    {
        $this->resource = 'Magento_Catalog::products';
        $this->uri = 'backend/yireo_react/ajax/productLabel';
        $this->httpMethod = 'GET';
        parent::setUp();
    }
}
