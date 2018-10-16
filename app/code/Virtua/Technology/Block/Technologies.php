<?php

namespace Virtua\Technology\Block;

use Magento\Framework\View\Element\Template;
use Virtua\Technology\Model\ResourceModel\Item\Collection;
use Virtua\Technology\Model\ResourceModel\Item\CollectionFactory;

class Technologies extends Template
{
    private $collectionFactory;

    /**
     * Technologies constructor.
     * @param Template\Context $context
     * @param CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CollectionFactory $collectionFactory,
        array $data = []
    )
    {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * @param $item
     * @return string
     */
    public function showLogo($item)
    {

        if ($item->getLogo()) {
            $url = $this->getUrl('pub/media/image') . 'tmp/' . $item->getLogo();
        } else {
            $url = $this->getUrl('pub/media/catalog') . 'product' . '/placeholder/default/placeholder-image.jpg';
        }
        return $url;
    }

    /**
     * @return Collection
     */
    public function getTechnologies()
    {
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 5;
        $collection = $this->collectionFactory->create();
        $collection->setOrder('name', 'ASC');
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);
        $collection->getItems();
        return $collection;
    }

    /**
     * @return Template|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->pageConfig->getTitle()->set(__('Technologies'));

        if ($this->getTechnologies()) {
            $pager = $this->getLayout()->createBlock('Magento\Theme\Block\Html\Pager', 'custom.pager');
            $pager->setAvailableLimit([5 => 5, 10 => 10, 20 => 20, 50 => 50]);
            $pager->setCollection($this->getTechnologies());
            $this->setChild('pager', $pager);
        }
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}