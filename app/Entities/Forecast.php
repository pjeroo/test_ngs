<?php

namespace App\Entities;

class Forecast implements \Countable
{
    private $forecast_items;

    public function __construct(array $raw)
    {
        foreach ($raw as $key => $value) {
            $this->addForecastItem(new ForecastItem($key, $value['current']));
        }
    }

    public function addForecastItem(ForecastItem $item)
    {
        $this->forecast_items[] = $item;
    }

    /**
     * @param bool $as_array
     * @return mixed
     */
    public function getForecastItems($as_array = false)
    {
        if ($as_array) {
            $array = [];

            foreach ($this->forecast_items as $item) {
                $array[] = get_object_vars($item);
            }

            return $array;
        } else {
            return $this->forecast_items;
        }
    }

    public function count()
    {
        return count($this->forecast_items);
    }
}