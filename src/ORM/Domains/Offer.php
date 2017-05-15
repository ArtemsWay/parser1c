<?php

namespace ArtemsWay\Parser1C\ORM\Domains;

class Offer extends Domain
{
    /**
     * Ид продукта.
     *
     * @var string
     */
    public $productId;

    /**
     * Ид характеристики.
     *
     * @var string
     */
    public $characteristicId;

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
     * @var number
     */
    public $count;

    /**
     * @var array
     */
    public $prices = [];

    /**
     * @var array
     */
    public $warehouses = [];
}
