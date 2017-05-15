<?php

namespace ArtemsWay\Parser1C\ORM\Domains;

class Warehouse extends Domain
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var array|null
     */
    public $address;

    /**
     * @var array
     */
    public $contacts = [];
}
