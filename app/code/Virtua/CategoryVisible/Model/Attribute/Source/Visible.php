<?php

namespace Virtua\CategoryVisible\Model\Attribute\Source;

use Magento\Customer\Model\ResourceModel\Group\Collection;

class Visible extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    protected $_customerGroupCollection;

    /**
     * Visible constructor.
     * @param Collection $customerGroupCollection
     */
    public function __construct(Collection $customerGroupCollection)
    {
        $this->_customerGroupCollection = $customerGroupCollection;
    }

    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $customerGroups = $this->_customerGroupCollection->getItems();
        $arr = [];
        foreach ($customerGroups as $index => $item) {
            $arr[$index] = [
                'value' => ($item->getId()+1),
                'label' => $item->getCode()
            ];
        }
        return $arr;
    }
}
