<?php

namespace App\Services;

use App\Core\DataProvider;

class ForecastService extends AbstractService
{
    /**
     * @var DataProvider
     */
    private $data_provider;

    public function __construct($mongo_provider, $monolog = null)
    {
        parent::__construct($mongo_provider, $monolog);
        $this->data_provider = new DataProvider();
    }

    public function getApiForecast($city, $as_json = false)
    {
        $forecast = $this->data_provider->getForecasts(['"' . $city . '"']);
        return $forecast ?
            $as_json ?
                json_encode(['forecast' => $forecast->getForecastItems()[0], 'archive' => $this->getChartForecast($city)])
                : ['forecast' => $forecast->getForecastItems()[0], 'archive' => $this->getChartForecast($city)]
            : null;
    }

    public function getApiForecasts()
    {
        $cities = array_map(function($city) {
            return '"' . $city['_id'] . '"';
        }, $this->getAllCities());

        $forecasts = $this->data_provider->getForecasts($cities);

        return $forecasts;
    }

    public function getDbForecast($city)
    {
        return iterator_to_array($this->getMongo()->safeSelect('ngs', 'forecasts', [
            'city_id' => $city,
            'created' => [
                '$lt' => new \MongoDate(time()),
                '$gt' => new \MongoDate(time() - (60 * 60 * 24))
            ]
        ]));
    }

    public function getChartForecast($city, $as_json = false)
    {
        $forecast = $this->getDbForecast($city);
        $chart_data = [];

        foreach ($forecast as $item) {
            $chart_data['title'][] = $item['date'] . ' ' . $item['time'];
            $chart_data['pressure'][] = $item['pressure'];
            $chart_data['wind'][] = $item['wind'];
            $chart_data['humidity'][] = $item['humidity'];
            $chart_data['temperature'][] = (float)$item['temperature'];
            $chart_data['heat_index'][] = $item['heat_index'];
        }

        return $as_json ? json_encode($chart_data) : $chart_data;
    }

    public function insertForecasts()
    {
        $forecasts = $this->getApiForecasts();

        if (!empty($forecasts)) {
            return $this->getMongo()->safeBatchInsert('ngs', 'forecasts', $forecasts->getForecastItems(true));
        } else {
            return false;
        }
    }

    public function getAllCities()
    {
        return iterator_to_array($this->getMongo()->safeSelect('ngs', 'cities'));
    }

    public function updateCities()
    {
        $cities = $this->data_provider->getAllCities();
        $query_array = [];

        foreach ($cities as $city) {
            $query_array[] = [
                '_id' => $city->getAlias(),
                'title' => $city->getTitle()
            ];
        }

        return $this->getMongo()->safeBatchInsert('ngs', 'cities', $query_array, ['continueOnError' => true]);
    }
}