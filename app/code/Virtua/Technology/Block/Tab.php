<?php

namespace Virtua\Technology\Block;

use Magento\Framework\View\Element\Template;
use Magento\Backend\Block\Template\Context;
use Virtua\Technology\Model\ResourceModel\Item\CollectionFactory as TechnologyCollectionFactory;
use Magento\Framework\Registry;

class Tab extends Template
{
    private $registry;
    private $technologyCollectionFactory;

    /**
     * Tab constructor.
     * @param Context $context
     * @param Registry $registry
     * @param TechnologyCollectionFactory $technologyCollectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        TechnologyCollectionFactory $technologyCollectionFactory,
        array $data = []
    )
    {
        $this->registry = $registry;
        $this->technologyCollectionFactory = $technologyCollectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return null|\Virtua\Technology\Model\ResourceModel\Item\Collection
     */
    public function getItem()
    {
        $product = $this->registry->registry('current_product');
        if (!empty($product->getCustomAttribute('technology'))) {
            $attributes = $product->getCustomAttribute('technology')->getValue();
            $technologyIds = explode(',', $attributes);
            $collection = $this->technologyCollectionFactory->create();
            $collection->addFieldToSelect(['name', 'description', 'logo'])->addFieldToFilter('entity_id', $technologyIds);
            return $collection;
        }
        return null;
    }

    /**
     * @param $item
     * @return string
     */
    public function showImage($item)
    {
        if ($item->getLogo()) {
            $url = $this->getUrl('pub/media/image') . 'tmp/' . $item->getLogo();
        } else {
            $url = $this->getUrl('pub/media/catalog') . 'product' . '/placeholder/default/placeholder-image.jpg';
        }
        return $url;
    }
}