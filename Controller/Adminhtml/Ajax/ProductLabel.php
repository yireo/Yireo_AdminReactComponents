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
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class ProductLabel extends AbstractLabel
{
    const ADMIN_RESOURCE = 'Yireo_AdminReactComponents::index';

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param Context $context
     * @param ProductRepositoryInterface $productRepository
     * @param Http $request
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository,
        Http $request,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context, $request, $resultJsonFactory);
        $this->productRepository = $productRepository;
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    protected function getLabel(): string
    {
        $id = $this->getId();
        if (!$id > 0) {
            throw new NoSuchEntityException(__('Empty ID'));
        }

        $product = $this->productRepository->getById($id);

        return $product->getName() . ' (' . $product->getSku() . ')';
    }

    /**
     * @return string
     */
    protected function getEmptyLabel(): string
    {
        return 'No product found';
    }
}
