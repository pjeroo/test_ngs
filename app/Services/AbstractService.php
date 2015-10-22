<?php

namespace App\Services;

use App\Core\MongoProvider;

class AbstractService
{
    /**
     * @var \Monolog\Logger
     */
    private $monolog;

    /**
     * @var MongoProvider
     */
    private $mongo_provider;

    public function __construct($mongo, $monolog)
    {
        $this->monolog = $monolog;
        $this->mongo_provider = $mongo;
    }

    protected function getMongo()
    {
        return $this->mongo_provider;
    }

    protected function getLogger()
    {
        return $this->monolog;
    }
}