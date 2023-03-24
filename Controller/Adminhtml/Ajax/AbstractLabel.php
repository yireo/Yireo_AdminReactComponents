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

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\NoSuchEntityException;

abstract class AbstractLabel extends Action
{
    const ADMIN_RESOURCE = 'Yireo_AdminReactComponents::index';

    /**
     * @var Http
     */
    protected $request;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @param Context $context
     * @param Http $request
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        Http $request,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->request = $request;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Index action
     *
     * @return Json
     */
    public function execute(): Json
    {
        try {
            $data = [
                'id' => $this->getId(),
                'label' => $this->getLabel(),
            ];
        } catch (NoSuchEntityException $e) {
            $data = [
                'id' => 0,
                'label' => __($this->getEmptyLabel()),
            ];
        }

        return $this->resultJsonFactory->create()->setData($data);
    }

    /**
     * @return int
     */
    protected function getId(): int
    {
        return (int)$this->request->getParam('id');
    }

    /**
     * @return string
     */
    abstract protected function getEmptyLabel(): string;

    /**
     * @return string
     */
    abstract protected function getLabel(): string;
}
