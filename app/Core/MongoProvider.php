<?php

namespace App\Core;

class MongoProvider
{
    /**
     * @var \MongoClient
     */
    private $mongo;

    /**
     * @var \Monolog\Logger
     */
    private $monolog;

    public function __construct(array $options, $monolog = null)
    {
        $this->monolog = $monolog;
        $this->mongo = new \MongoClient("mongodb://" . $options['host'], $options['credentials']);
    }

    public function getConnection()
    {
        return $this->mongo;
    }

    public function getLogger()
    {
        return $this->monolog;
    }

    public function safeSelect($db, $collection, array $data = [])
    {
        try {
            return $this->mongo->{$db}->{$collection}->find($data);
        } catch (\MongoException $e) {
            if ($this->monolog) {
                $this->monolog->error('Mongo error', ['class' => __CLASS__, 'message' => $e->getMessage()]);
            }
            return false;
        }
    }

    public function safeInsert($db, $collection, array $data, array $options = [])
    {
        try {
            return $this->mongo->{$db}->{$collection}->insert($data, $options);
        } catch (\MongoException $e) {
            if ($this->monolog) {
                $this->monolog->error('Mongo error', ['class' => __CLASS__, 'message' => $e->getMessage()]);
            }
            return false;
        }
    }

    public function safeBatchInsert($db, $collection, array $data, array $options = [])
    {
        try {
            return $this->mongo->{$db}->{$collection}->batchInsert($data, $options);
        } catch (\MongoException $e) {
            if ($this->monolog) {
                $this->monolog->error('Mongo error', ['class' => __CLASS__, 'message' => $e->getMessage()]);
            }
            return false;
        }
    }
}