<?php

namespace Virtua\Delivery\Model\Config\Source;

class ConfigOption implements \Magento\Framework\Option\ArrayInterface
{
    public $arr = array(
        'Mon' => "Monday",
        'Tue' => "Tuesday",
        'Wed' => "Wednesday",
        'Thu' => "Thursday",
        'Fri' => "Friday",
        'Sat' => "Saturday",
        'Sun' => "Sunday"
    );

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $ret = [];
        foreach ($this->arr as $key => $value) {
            $ret[] = [
                'value' => $key,
                'label' => $value
            ];
        }

        return $ret;
    }

    /**
     * @return array
     */
    public function getOriginalOption()
    {
        return $this->arr;
    }
}