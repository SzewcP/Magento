<?php

namespace Virtua\Technology\Model;

use Magento\Framework\Model\AbstractModel;

class Item extends AbstractModel
{
    protected $_eventPrefix = 'virtua_technology';

    protected function _construct()
    {
        $this->_init(\Virtua\Technology\Model\ResourceModel\Item::class);
    }
}