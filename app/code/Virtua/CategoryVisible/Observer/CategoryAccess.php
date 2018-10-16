<?php

namespace Virtua\CategoryVisible\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Http\Context;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\App\ResponseFactory;
use Magento\Framework\UrlInterface;

class CategoryAccess implements ObserverInterface
{
    protected $context;
    protected $responseFactory;
    protected $customerSession;
    protected $url;
    protected $collectionFactory;

    /**
     * CategoryAccess constructor.
     * @param Context $context
     * @param ResponseFactory $responseFactory
     * @param CollectionFactory $collectionFactory
     * @param Context $httpContext
     * @param UrlInterface $url
     */
    public function __construct(
        Context $context,
        ResponseFactory $responseFactory,
        CollectionFactory $collectionFactory,
        Context $httpContext,
        UrlInterface $url
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->context = $context;
        $this->responseFactory = $responseFactory;
        $this->customerSession = $httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_GROUP);
        $this->url = $url;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customerGroup = $this->getCustomerSession();
        $categories = $this->collectionFactory->create();

        $categories->addAttributeToSelect(['category_visible', 'id']);
        $categories->addAttributeToFilter('category_visible', ['eq' => $customerGroup]);
        $categories->getItems();
        foreach ($categories as $category) {
            $categoryUrls[] = $category->getUrl();
        }
        if (in_array($this->url->getCurrentUrl(), $categoryUrls)) {
            $this->responseFactory->create()->setRedirect('/')->sendResponse();
            die;
        }
    }

    /**
     * @return int|mixed|null
     */
    public function getCustomerSession()
    {
        return ($this->customerSession) + 1;
    }
}