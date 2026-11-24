<?php

declare(strict_types=1);

namespace Yireo\AdminReactComponents\Test\Integration\Controller\Adminhtml\Ajax;

use Magento\TestFramework\TestCase\AbstractBackendController;

/**
 * @magentoAppArea adminhtml
 */
class CustomerSearchTest extends AbstractBackendController
{
    protected function setUp(): void
    {
        $this->resource = 'Magento_Customer::manage';
        $this->uri = 'backend/yireo_react/ajax/customerSearch';
        $this->httpMethod = 'GET';
        parent::setUp();
    }

    /**
     * @magentoDataFixture Magento/Customer/_files/customer.php
     */
    public function testSortByGroupLabelReturnsResults(): void
    {
        $this->getRequest()->setMethod('GET')->setParams([
            'search' => 'customer@example.com',
            'sortField' => 'group_label',
            'sortDirection' => 'DESC',
        ]);
        $this->dispatch($this->uri);

        $this->assertSame(200, $this->getResponse()->getHttpResponseCode());
        $data = json_decode((string)$this->getResponse()->getBody(), true);
        $this->assertIsArray($data);
        $this->assertSame('customer@example.com', $data[0]['email'] ?? null);
    }

    /**
     * @magentoDataFixture Magento/Customer/_files/customer.php
     */
    public function testUnknownSortFieldAndDirectionAreIgnored(): void
    {
        $this->getRequest()->setMethod('GET')->setParams([
            'search' => 'customer@example.com',
            'sortField' => 'password_hash',
            'sortDirection' => 'foobar',
        ]);
        $this->dispatch($this->uri);

        $this->assertSame(200, $this->getResponse()->getHttpResponseCode());
        $data = json_decode((string)$this->getResponse()->getBody(), true);
        $this->assertIsArray($data);
        $this->assertCount(1, $data);
    }

    /**
     * @magentoDataFixture Magento/Customer/_files/customer.php
     */
    public function testWildcardsAreTreatedLiterally(): void
    {
        $this->getRequest()->setMethod('GET')->setParams(['search' => '%%']);
        $this->dispatch($this->uri);

        $this->assertSame(200, $this->getResponse()->getHttpResponseCode());
        $this->assertSame([], json_decode((string)$this->getResponse()->getBody(), true));
    }
}
