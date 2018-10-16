<?php

namespace Virtua\Technology\Ui;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Virtua\Technology\Model\ResourceModel\Item\CollectionFactory;

class DataProvider extends AbstractDataProvider
{
    protected $collection;
    protected $result;
    protected $_storeManager;

    /**
     * DataProvider constructor.
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $collectionFactory->create();
        $this->_storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getData()
    {
        if (isset($this->result)) {
            return $this->result;
        }
        $items = $this->collection->getItems();
        foreach ($items as $item) {
            $this->result[$item->getId()]['general'] = $item->getData();

            if ($item->getLogo() != '') {
                $img['logo'][0]['name'] = $item->getLogo();
                $img['logo'][0]['url'] = $this->getMediaUrl() . 'image/tmp/' . $item->getLogo();
                $fullData = $this->result;
                $this->result[$item->getId()]['general'] = array_merge($fullData[$item->getId()]['general'], $img);
            }
        }
        return $this->result;
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMediaUrl()
    {
        $mediaUrl = $this->_storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        return $mediaUrl;
    }
}
