<?php

namespace App\Entities;

class City
{
    private $alias;
    private $title;

    function __construct($alias, $title)
    {
        $this->alias = $alias;
        $this->title = $title;
    }


    /**
     * @return mixed
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param mixed $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}