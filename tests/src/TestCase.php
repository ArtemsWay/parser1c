<?php

namespace ArtemsWay\Parser1C\Tests;

use PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    public static $importFile = __DIR__ . '/../fixtures/import0_1.xml';
    public static $offersFile = __DIR__ . '/../fixtures/offers0_1.xml';
}
