<?php

namespace Virtua\PriceVisible\Plugin;

class Price extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $isCustomerLoggedIn;

    public function __construct(
        \Magento\Framework\App\Http\Context $httpContext
    )
    {
        $this->isCustomerLoggedIn = $httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
    }

    public function getcustomerSession()
    {
        return $this->isCustomerLoggedIn;
    }

    public function afterRenderAmount(\Magento\Catalog\Pricing\Render\FinalPriceBox $subject, $result)
    {
        if ($this->getcustomerSession()) {
            return $result;
        } else {
            return null;
        }
    }
}