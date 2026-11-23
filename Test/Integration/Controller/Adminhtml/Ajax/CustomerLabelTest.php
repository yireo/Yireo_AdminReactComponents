<?php

declare(strict_types=1);

namespace Yireo\AdminReactComponents\Test\Integration\Controller\Adminhtml\Ajax;

use Magento\TestFramework\TestCase\AbstractBackendController;

/**
 * @magentoAppArea adminhtml
 */
class CustomerLabelTest extends AbstractBackendController
{
    protected function setUp(): void
    {
        $this->resource = 'Magento_Customer::manage';
        $this->uri = 'backend/yireo_react/ajax/customerLabel';
        $this->httpMethod = 'GET';
        parent::setUp();
    }

    /**
     * @magentoDataFixture Magento/Customer/_files/customer.php
     */
    public function testReturnsCustomerDataForAuthorizedAdmin(): void
    {
        $this->getRequest()->setMethod('GET')->setParam('id', 1);
        $this->dispatch($this->uri);

        $this->assertSame(200, $this->getResponse()->getHttpResponseCode());
        $data = json_decode((string)$this->getResponse()->getBody(), true);
        $this->assertIsArray($data);
        $this->assertSame('customer@example.com', $data['email']);
    }

    /**
     * @magentoDataFixture Magento/Customer/_files/customer.php
     */
    public function testDoesNotDiscloseCustomerDataToAnonymousVisitor(): void
    {
        $this->_auth->logout();

        $this->getRequest()->setMethod('GET')->setParam('id', 1);
        $this->dispatch($this->uri);

        $this->assertStringNotContainsString('customer@example.com', (string)$this->getResponse()->getBody());
    }

    /**
     * @magentoDataFixture Magento/Customer/_files/customer.php
     */
    public function testDoesNotDiscloseCustomerDataWithoutAclResource(): void
    {
        $acl = $this->_objectManager->get(\Magento\Framework\Acl\Builder::class)->getAcl();
        $acl->deny($this->_auth->getUser()->getRoles(), $this->resource);

        $this->getRequest()->setMethod('GET')->setParam('id', 1);
        $this->dispatch($this->uri);

        $this->assertSame(403, $this->getResponse()->getHttpResponseCode());
        $this->assertStringNotContainsString('customer@example.com', (string)$this->getResponse()->getBody());
    }

    public function testReturnsEmptyDataForInvalidId(): void
    {
        $this->getRequest()->setMethod('GET')->setParam('id', '-1');
        $this->dispatch($this->uri);

        $data = json_decode((string)$this->getResponse()->getBody(), true);
        $this->assertSame(0, $data['id']);
    }
}
