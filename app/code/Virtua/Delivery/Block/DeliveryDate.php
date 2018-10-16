<?php

namespace Virtua\Delivery\Block;

use function GuzzleHttp\Psr7\str;
use Magento\Framework\Registry;

class DeliveryDate extends \Magento\Framework\View\Element\Template
{
    private $registry;

    /**
     * DeliveryDate constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(\Magento\Framework\View\Element\Template\Context $context,
                                Registry $registry,
                                array $data = [])
    {
        $this->registry = $registry;
        parent::__construct($context);
    }

    /**
     * @return false|null|string
     */
    public function calculateEstimatedDeliveryDate()
    {
        $days_not_shipping = $this->_scopeConfig->getValue('delivery/delivery_options/days_list');
        $days_not_shipping = explode(',', $days_not_shipping);
        $ships_until = $this->_scopeConfig->getValue('delivery/delivery_options/ships_until');
        $holidays_start = $this->_scopeConfig->getValue('delivery/delivery_options/start_date');
        $holidays_end = $this->_scopeConfig->getValue('delivery/delivery_options/end_date');

        $holidays_dates = $this->getHolidaysCollection($holidays_start, $holidays_end);

        $product = $this->registry->registry('current_product');
        $shipping_in = $product->getCustomAttribute('shipping_in')->getValue();
        if($shipping_in!=null){
        $today = time();
        if ((date('G') < date('G', strtotime($ships_until)))) {

            if (in_array(date('D-d-m-Y', $today), $holidays_dates)) {
                $today = $this->getAfterHolidayDeliveryDate($holidays_end, $days_not_shipping);
            }
            $tomorrow = strtotime("+1 day", $today);
            $after_not_shipping_day = $this->getAfterNotShippingDay($tomorrow, $days_not_shipping);
            $shipping_in_days = strtotime("+" . ($shipping_in - 1) . "day", $after_not_shipping_day);
            $estimated_delivery_date = $this->getAfterNotShippingDay($shipping_in_days, $days_not_shipping);

            if ((in_array(date('D-d-m-Y', $estimated_delivery_date), $holidays_dates))
                && (date('D-d-m-Y', $estimated_delivery_date) != $holidays_dates[0])) {
                $estimated_delivery_date = $this->getAfterHolidayDeliveryDate($holidays_end, $days_not_shipping);
            }
            return date('D d.m.Y', $estimated_delivery_date);
        } elseif ((date('G') >= date('G', strtotime($ships_until)))) {
            if (in_array(date('D-d-m-Y', $today), $holidays_dates)) {
                $today = $this->getAfterHolidayDeliveryDate($holidays_end, $days_not_shipping);
            }
            $tomorrow = strtotime("+ 1 day", $today);
            $after_not_shipping_day = $this->getAfterNotShippingDay($tomorrow, $days_not_shipping);
            $shipping_in_days = strtotime("+" . ($shipping_in) . "day", $after_not_shipping_day);
            $estimated_delivery_date = $this->getAfterNotShippingDay($shipping_in_days, $days_not_shipping);

            if (in_array(date('D-d-m-Y', $estimated_delivery_date), $this->getHolidaysCollection($holidays_start, $holidays_end))) {
                $after_holiday_date = $this->getAfterHolidayDeliveryDate($holidays_end, $days_not_shipping);
                $estimated_delivery_date = strtotime("+" . ($shipping_in) . "day", $after_holiday_date);
            }
            return date('D d.m.Y', $estimated_delivery_date);
        }
        return null;
    }}
    /**
     * @param $day
     * @param $days_not_shipping
     * @return false|int
     */
    public function getAfterNotShippingDay($day, $days_not_shipping)
    {
        while (in_array(date('D', $day), $days_not_shipping)) {
            $day = strtotime("+ 1 day", $day);
        }
        return $day;
    }

    /**
     * @param $holidays_start
     * @param $holidays_end
     * @return array
     */
    public function getHolidaysCollection($holidays_start, $holidays_end)
    {
        $output_format = 'D-d-m-Y';
        $step = '+1 day';
        $holidays_start = str_replace('/', '-', $holidays_start);
        $holidays_end = str_replace('/', '-', $holidays_end);
        $start = date(strtotime($holidays_start));
        $end = date(strtotime($holidays_end));
        $dates = [];

        while ($start <= $end) {
            $dates[] = date($output_format, $start);
            $start = strtotime($step, $start);
        }
        return $dates;
    }

    /**
     * @param $holidays_end
     * @param $days_not_shipping
     * @return mixed
     */
    public function getAfterHolidayDeliveryDate($holidays_end, $days_not_shipping)
    {
        $holidays_end = str_replace('/', '-', $holidays_end);
        $end = date(strtotime($holidays_end));
        $next = strtotime("+1 day", $end);
        $after_holiday_date = $this->getAfterNotShippingDay($next, $days_not_shipping);

        return $after_holiday_date;
    }
}