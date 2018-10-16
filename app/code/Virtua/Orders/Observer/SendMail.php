<?php

namespace Virtua\Orders\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Sales\Model\Order;
use Magento\Framework\App\Config\ScopeConfigInterface;

class SendMail implements ObserverInterface
{
    protected $storeManager;
    protected $_transportBuilder;
    protected $inlineTranslation;
    protected $order;
    protected $scopeConfig;

    /**
     * SendMail constructor.
     * @param StoreManagerInterface $storeManager
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $state
     * @param Order $order
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        TransportBuilder $transportBuilder,
        StateInterface $state, Order $order,
        ScopeConfigInterface $scopeConfig)
    {

        $this->storeManager = $storeManager;
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $state;
        $this->scopeConfig = $scopeConfig;
        $this->order = $order;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $order = $observer->getEvent()->getOrder();
        $name = $order->getCustomerFirstname();
        $email = $order->getCustomerEmail();
        $id = $order->getId();
        if ($order->getCustomerIsGuest()) {
            $template = 'sales_email_order_virtua_cancel_order_template_guest';
        } else {
            $template = 'sales_email_order_virtua_cancel_order_template';
        }


        $templateOptions = array('area' => \Magento\Framework\App\Area::AREA_ADMINHTML, 'store' => $this->storeManager->getStore()->getId());
        $templateVars = array(
            'store' => $this->storeManager->getStore(),
            'customer_name' => 'Shop',
            'message' => 'Dear customer ' . $name . ', your order ' . $id . ' has been cancelled'
        );
        $from = array('email' => "sklep@sklep.pl", 'name' => 'Shop');
        $this->inlineTranslation->suspend();
        $to = array($email);
        $transport = $this->_transportBuilder->setTemplateIdentifier($template)
            ->setTemplateOptions($templateOptions)
            ->setTemplateVars($templateVars)
            ->setFrom($from)
            ->addTo($to)
            ->getTransport();
        $transport->sendMessage();
        $this->inlineTranslation->resume();
    }
}