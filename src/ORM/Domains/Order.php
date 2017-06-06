<?php

namespace ArtemsWay\Parser1C\ORM\Domains;

class Order extends Domain
{
    /**
     * @var string
     */
    public $number;

    /**
     * @var float
     */
    public $price;

    /**
     * @var string
     */
    public $date;

    /**
     * @var string
     */
    public $time;

    /**
     * @var string
     */
    public $comment;

    /**
     * @var array
     */
    public $products = [];

    /**
     * @var array
     */
    public $requisites = [];
}
