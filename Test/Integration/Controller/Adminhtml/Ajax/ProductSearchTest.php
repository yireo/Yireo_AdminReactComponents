<?php

declare(strict_types=1);

namespace Yireo\AdminReactComponents\Test\Integration\Controller\Adminhtml\Ajax;

use Magento\TestFramework\TestCase\AbstractBackendController;

/**
 * @magentoAppArea adminhtml
 */
class ProductSearchTest extends AbstractBackendController
{
    protected function setUp(): void
    {
        $this->resource = 'Magento_Catalog::products';
        $this->uri = 'backend/yireo_react/ajax/productSearch';
        $this->httpMethod = 'GET';
        parent::setUp();
    }

    /**
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     */
    public function testSearchBySku(): void
    {
        $this->getRequest()->setMethod('GET')->setParams([
            'search' => 'simple',
            'sortField' => 'sku',
            'sortDirection' => 'ASC',
        ]);
        $this->dispatch($this->uri);

        $this->assertSame(200, $this->getResponse()->getHttpResponseCode());
        $data = json_decode((string)$this->getResponse()->getBody(), true);
        $this->assertIsArray($data);
        $this->assertContains('simple', array_column($data, 'sku'));
    }
}
