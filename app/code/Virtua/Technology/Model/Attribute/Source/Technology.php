<?php

namespace Virtua\Technology\Model\Attribute\Source;

use Virtua\Technology\Model\ResourceModel\Item\CollectionFactory;

class Technology extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    protected $collectionFactory;

    /**
     * Technology constructor.
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $items = $this->collectionFactory->create()->getItems();
        $arr = [];
        foreach ($items as $index => $item) {
            $arr[$index] = [
                'value' => $item->getId(),
                'label' => $item->getName()
            ];
        }
        return $arr;
    }
}
