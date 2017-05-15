<?php

namespace ArtemsWay\Parser1C\ORM\Domains;

class PriceType extends Domain
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $currency;

    /**
     * @var array
     */
    public $taxes = [];
}
