<?php

namespace ArtemsWay\Parser1C\ORM\Domains;

class Property extends Domain
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type;

    /**
     * @var array
     */
    public $values = [];
}
