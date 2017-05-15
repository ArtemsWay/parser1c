<?php

namespace ArtemsWay\Parser1C\Tests\Parsers\DOM;

use ArtemsWay\Parser1C\Tests\TestCase;
use ArtemsWay\Parser1C\ORM\Domains\Offer;
use ArtemsWay\Parser1C\ORM\Domains\PriceType;
use ArtemsWay\Parser1C\ORM\Domains\Warehouse;
use ArtemsWay\Parser1C\Parsers\DOM\OffersParser;

class OffersParserTest extends TestCase
{
    public function testItCanParseSchemaVersion()
    {
        $parser = new OffersParser;

        $data = $parser->load(self::$offersFile)->parseSchemaVersion()->getData();

        $this->assertEquals('2.07', $data['schemaVersion']);
    }

    public function testItCanParseImportTime()
    {
        $parser = new OffersParser;

        $data = $parser->load(self::$offersFile)->parseImportTime()->getData();

        $this->assertEquals('2017-05-13T21:41:15', $data['importTime']);
    }

    public function testItCanParseOnlyChanges()
    {
        $parser = new OffersParser;

        $data = $parser->load(self::$offersFile)->parseOnlyChanges()->getData();

        $this->assertFalse($data['onlyChanges']);
    }

    public function testItCanParsePriceTypes()
    {
        $parser = new OffersParser;

        $data = $parser->load(self::$offersFile)->parsePriceTypes()->getData();

        $this->assertCount(1, $data['priceTypes']);

        $price = array_shift($data['priceTypes']);

        $this->assertInstanceOf(PriceType::class, $price);

        $this->assertEquals('8dc13405-2470-11e0-aeec-0015e9b8c48d', $price->id);
        $this->assertEquals('Продажа оптом', $price->name);
        $this->assertEquals('RUB', $price->currency);
        $this->assertCount(1, $price->taxes);
    }

    public function testItCanParseWarehouses()
    {
        $parser = new OffersParser;

        $data = $parser->load(self::$offersFile)->parseWarehouses()->getData();

        $this->assertCount(2, $data['warehouses']);

        $warehouse = array_shift($data['warehouses']);

        $this->assertInstanceOf(Warehouse::class, $warehouse);

        $this->assertEquals('6f87e83f-722c-11df-b336-0011955cba6b', $warehouse->id);
        $this->assertEquals('Центральный склад', $warehouse->name);
        $this->assertEquals('РОССИЯ ,Регион=Москва г ,Улица=1905 года ул ,', $warehouse->address['representation']);
        $this->assertCount(3, $warehouse->address['fields']);
    }

    public function testItCanParseOffers()
    {
        $parser = new OffersParser;

        $data = $parser->load(self::$offersFile)->parseOffers()->getData();

        $this->assertCount(9, $data['offers']);

        $offerWithCharacteristics = array_shift($data['offers']);

        $this->assertCount(2, $offerWithCharacteristics);

        $offer = array_pop($data['offers']);

        $this->assertInstanceOf(Offer::class, $offer);

        $this->assertEquals('0c6013de-ea5e-11e0-a7cd-e0cb4ed5f6e4', $offer->id);
        $this->assertEquals('NYM 5х1,5', $offer->sku);
        $this->assertEquals('Кабель NYM (Севкабель) х', $offer->name);
        $this->assertCount(3, $offer->unit);
        $this->assertCount(1, $offer->prices);
        $this->assertEquals(514, $offer->count);
        $this->assertCount(2, $offer->warehouses);
    }
}
