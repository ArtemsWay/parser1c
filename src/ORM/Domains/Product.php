<?php

namespace ArtemsWay\Parser1C\ORM\Domains;

class Product extends Domain
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $sku;

    /**
     * @var array|null
     */
    public $unit;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $category;

    /**
     * @var boolean
     */
    public $deleted;

    /**
     * @var array
     */
    public $images = [];

    /**
     * @var array
     */
    public $properties = [];

    /**
     * @var array
     */
    public $characteristics = [];

    /**
     * @var array
     */
    public $requisites = [];
}
