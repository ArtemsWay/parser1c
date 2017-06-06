<?php

namespace ArtemsWay\Parser1C\Tests\Parsers\DOM;

use ArtemsWay\Parser1C\Tests\TestCase;
use ArtemsWay\Parser1C\ORM\Domains\Order;
use ArtemsWay\Parser1C\Parsers\DOM\OrderParser;

class OrderParserTest extends TestCase
{
    public function testItCanParseSchemaVersion()
    {
        $parser = new OrderParser;

        $data = $parser->load(self::$orderFile)->parseSchemaVersion()->getData();

        $this->assertEquals('2.07', $data['schemaVersion']);
    }

    public function testItCanParseImportTime()
    {
        $parser = new OrderParser;

        $data = $parser->load(self::$orderFile)->parseImportTime()->getData();

        $this->assertEquals('2016-10-11T18:50:03', $data['importTime']);
    }

    public function testItCanParseProducts()
    {
        $parser = new OrderParser;

        $data = $parser->load(self::$orderFile)->parseOrders()->getData();

        $this->assertCount(2, $data['orders']);

        $order = array_shift($data['orders']);

        $this->assertInstanceOf(Order::class, $order);

        $this->assertEquals('76c2a49c-217e-11e6-ae3c-6805ca05f260', $order->id);
        $this->assertCount(2, $order->products);
        $this->assertCount(10, $order->requisites);
    }
}
