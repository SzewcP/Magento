<?php

namespace Virtua\FeaturedProducts\Block;

class Display extends \Magento\Framework\View\Element\Template
{
    protected $_productCollection;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory)
    {
        $this->_productCollection = $productCollectionFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getFeaturedCollection()
    {
        $numberOfProductsToDisplay = $this->_scopeConfig->getValue('featured/featured_options/number');
        $displayFeatured = $this->_scopeConfig->getValue('featured/featured_options/display');

        if ($displayFeatured == 1) {
            $collection = $this->_productCollection->create();
            $collection->addAttributeToSelect(['is_featured', 'name', 'thumbnail']);
            $collection->addAttributeToFilter('is_featured', ['eq' => 1]);
            $collection->getSelect()->limit($numberOfProductsToDisplay);
            return $collection;
        } else {
            return null;
        }
    }

    public function getThumbnailUrl($product)
    {

        if ($product->getThumbnail()) {
            $url = $this->getUrl('pub/media/catalog') . 'product' . $product->getThumbnail();
        } else {
            $url = $this->getUrl('pub/media/catalog') . 'product' . '/placeholder/default/placeholder-image.jpg';
        }
        return $url;
    }
}