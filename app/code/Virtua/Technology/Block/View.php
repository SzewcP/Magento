<?php

namespace Virtua\Technology\Block;

use Magento\Framework\View\Element\Template;
use Virtua\Technology\Model\ResourceModel\Item\Collection;
use Virtua\Technology\Model\ResourceModel\Item\CollectionFactory;

class View extends Template
{
    private $collectionFactory;

    /**
     * View constructor.
     * @param Template\Context $context
     * @param CollectionFactory $collectionFactory
     * @param \Magento\Framework\App\Request\Http $request
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CollectionFactory $collectionFactory,
        \Magento\Framework\App\Request\Http $request,
        array $data = []
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->_request = $request;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Framework\DataObject
     */
    public function getItem()
    {
        $items = $this->collectionFactory->create()->getItems();
        $path = $this->_request->getPathInfo();
        $urlName = pathinfo($path, PATHINFO_BASENAME);
        foreach ($items as $item) {
            if ($urlName == $item['url']) {
                return $item;
            }
        }
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