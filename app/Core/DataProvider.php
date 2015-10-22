<?php

namespace App\Core;

use App\Entities\City;
use App\Entities\Forecast;

class DataProvider
{
    private $url = 'http://pogoda.ngs.ru/json';

    /**
     * @return array|bool
     */
    public function getAllCities()
    {
        $response = $this->request('{"method" : "getCities", "params" : []}', true);
        $cities = [];

        if (isset($response['result'])) {
            foreach ($response['result'] as $value) {
                $cities[] = new City($value['aliase'], $value['title']);
            }

            return $cities;
        } else {
            return false;
        }
    }

    public function getForecasts(array $cities)
    {
        if (empty($cities)) {
            return false;
        }

        $response = $this->request('{"method" : "getForecasts", "params" : {"names" : ["current"], "cities" : [' . implode(',', $cities) . ']}}', true);

        if (isset($response['result'])) {
            return new Forecast($response['result']);
        } else {
            return false;
        }
    }

    private function request($request, $json = false)
    {
        $curl = curl_init($this->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

        $response = curl_exec($curl);
        curl_close($curl);

        return $json ? json_decode($response, true) : $response;
    }
}