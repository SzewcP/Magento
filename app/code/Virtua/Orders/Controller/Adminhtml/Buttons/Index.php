<?php

namespace Virtua\Orders\Controller\Adminhtml\Buttons;

use Magento\Backend\App\Action\Context;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;

class Index extends \Magento\Backend\App\Action
{
    protected $collectionFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param CollectionFactory $orderCollectionFactory
     */
    public function __construct(
        Context $context,
        CollectionFactory $orderCollectionFactory
    )
    {
        $this->collectionFactory = $orderCollectionFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $orders = $this->collectionFactory->create()->getItems();
        $orderArr = [];
        $navigation = $this->_request->getParam('nav');
        foreach ($orders as $order) {
            $orderArr[] = $order->getId();
        }
        $orderId = $this->_request->getParam('order_id');

        if ($navigation == 'next') {
            $id = $orderId + 1;
            if (in_array($id, $orderArr)) {
                return $this->resultRedirectFactory->create()->setPath('sales/order/view/' . 'order_id/' . $id);
            }

        } elseif ($navigation == 'previous') {
            $id = $orderId - 1;
            if (in_array($id, $orderArr)) {
                return $this->resultRedirectFactory->create()->setPath('sales/order/view/' . 'order_id/' . $id);
            }
        }

        return $this->resultRedirectFactory->create()->setPath('sales/order/view/' . 'order_id/' . $orderId);
    }
}