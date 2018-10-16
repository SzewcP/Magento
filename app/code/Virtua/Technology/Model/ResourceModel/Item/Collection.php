<?php

namespace Virtua\Technology\Model\ResourceModel\Item;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Virtua\Technology\Model\Item;
use Virtua\Technology\Model\ResourceModel\Item as ItemResource;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    protected function _construct()
    {
        $this->_init(Item::class, ItemResource::class);
    }
}
