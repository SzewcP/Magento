<?php

namespace Virtua\CategoryVisible\Plugin\Block;

use Magento\Framework\Data\Tree\Node;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\App\Http\Context;

class Topmenu
{
    protected $collectionFactory;
    protected $customerSession;

    /**
     * Topmenu constructor.
     * @param CollectionFactory $collectionFactory
     * @param Context $httpContext
     */
    public function __construct(CollectionFactory $collectionFactory, Context $httpContext)
    {
        $this->collectionFactory = $collectionFactory;
        $this->customerSession = $httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_GROUP);
    }

    /**
     * @param \Magento\Theme\Block\Html\Topmenu $topmenu
     * @param $html
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeGetHtml(\Magento\Theme\Block\Html\Topmenu $topmenu, $html)
    {
        $customerGroup = $this->getCustomerSession();
        $categories = $this->collectionFactory->create();

        $categories->addAttributeToSelect(['category_visible', 'id']);
        $categories->addAttributeToFilter('category_visible', ['eq' => $customerGroup]);
        $categories->getItems();
        foreach ($categories as $category) {
            $categoryNodeIds[] = 'category-node-' . $category->getId();
        }

        $parent = $topmenu->getMenu()->getChildren();

        foreach ($parent as $child) {
            if (in_array($child->getId(), $categoryNodeIds)) {
                $parent->delete($child);
            }
        }

        foreach ($parent as $child) {

            $this->getAllChilds($child, $categoryNodeIds);
        }
    }

    /**
     * @param $subCategory
     * @param $categoryNodeIds
     */
    public function getAllChilds($subCategory, $categoryNodeIds)
    {
        foreach ($subCategory->getChildren() as $child) {
            if (in_array($child->getId(), $categoryNodeIds)) {
                $subCategory->getChildren()->delete($child);
            }
            if ($child->hasChildren()) {
                $this->getAllChilds($child, $categoryNodeIds);
            }
            continue;
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
