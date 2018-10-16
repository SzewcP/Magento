<?php

namespace Virtua\Orders\Plugin;

class OrdersPlugin
{
    /**
     * @param \Magento\Backend\Block\Widget\Button\Toolbar\Interceptor $subject
     * @param \Magento\Framework\View\Element\AbstractBlock $context
     * @param \Magento\Backend\Block\Widget\Button\ButtonList $buttonList
     */
    public function beforePushButtons(
        \Magento\Backend\Block\Widget\Button\Toolbar\Interceptor $subject,
        \Magento\Framework\View\Element\AbstractBlock $context,
        \Magento\Backend\Block\Widget\Button\ButtonList $buttonList
    )
    {
        $this->_request = $context->getRequest();
        if ($this->_request->getFullActionName() == 'sales_order_view') {
            $buttonList
                ->add(
                    'order_previous',
                    ['label' => __('Previous'), 'onclick' => 'setLocation(\'' . $context->getUrl('orders/buttons/index/') . 'nav/' . 'previous' . '\')'],
                    +1);
            $buttonList
                ->add(
                    'order_next',
                    ['label' => __('Next'), 'onclick' => 'setLocation(\'' . $context->getUrl('orders/buttons/index/') . 'nav/' . 'next' . '\')'],
                    +1);
        }
    }
}