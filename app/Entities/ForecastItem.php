<?php

namespace App\Entities;

class ForecastItem
{
    public $city_id;
    public $pressure;
    public $wind;
    public $humidity;

    public $heat_index;
    public $temperature;

    public $time_of_sunset;
    public $time_of_sunrise;

    public $precip_title;
    public $date;
    public $time;

    public $created;

    public function __construct($title, array $raw)
    {
        $this->city_id = $title;
        $this->pressure = $raw['pressure_avg'];
        $this->wind = $raw['wind_avg'];
        $this->humidity = $raw['humidity_avg'];
        $this->heat_index = $raw['heat_index'];
        $this->temperature = $raw['temp_current_c'];
        $this->time_of_sunset = $raw['time_of_sunset'];
        $this->time_of_sunrise = $raw['time_of_sunrise'];
        $this->precip_title = $raw['precip_title'];
        $this->date = $raw['date'];
        $this->time = date('H:i:s');
        $this->created = new \MongoDate(strtotime($raw['date'] . ' ' . date('H:i:s')));
    }

    public function serialize()
    {
        return json_encode($this);
    }

    /**
     * @return mixed
     */
    public function getPressure()
    {
        return $this->pressure;
    }

    /**
     * @param mixed $pressure
     */
    public function setPressure($pressure)
    {
        $this->pressure = $pressure;
    }

    /**
     * @return mixed
     */
    public function getWind()
    {
        return $this->wind;
    }

    /**
     * @param mixed $wind
     */
    public function setWind($wind)
    {
        $this->wind = $wind;
    }

    /**
     * @return mixed
     */
    public function getHumidity()
    {
        return $this->humidity;
    }

    /**
     * @param mixed $humidity
     */
    public function setHumidity($humidity)
    {
        $this->humidity = $humidity;
    }

    /**
     * @return mixed
     */
    public function getHeatIndex()
    {
        return $this->heat_index;
    }

    /**
     * @param mixed $heat_index
     */
    public function setHeatIndex($heat_index)
    {
        $this->heat_index = $heat_index;
    }

    /**
     * @return mixed
     */
    public function getTemperature()
    {
        return $this->temperature;
    }

    /**
     * @param mixed $temperature
     */
    public function setTemperature($temperature)
    {
        $this->temperature = $temperature;
    }

    /**
     * @return mixed
     */
    public function getTimeOfSunset()
    {
        return $this->time_of_sunset;
    }

    /**
     * @param mixed $time_of_sunset
     */
    public function setTimeOfSunset($time_of_sunset)
    {
        $this->time_of_sunset = $time_of_sunset;
    }

    /**
     * @return mixed
     */
    public function getTimeOfSunrise()
    {
        return $this->time_of_sunrise;
    }

    /**
     * @param mixed $time_of_sunrise
     */
    public function setTimeOfSunrise($time_of_sunrise)
    {
        $this->time_of_sunrise = $time_of_sunrise;
    }

    /**
     * @return mixed
     */
    public function getPrecipTitle()
    {
        return $this->precip_title;
    }

    /**
     * @param mixed $precip_title
     */
    public function setPrecipTitle($precip_title)
    {
        $this->precip_title = $precip_title;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }
}