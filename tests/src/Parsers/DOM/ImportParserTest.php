<?php

namespace ArtemsWay\Parser1C\Tests\Parsers\DOM;

use ArtemsWay\Parser1C\Tests\TestCase;
use ArtemsWay\Parser1C\ORM\Domains\Product;
use ArtemsWay\Parser1C\ORM\Domains\Property;
use ArtemsWay\Parser1C\ORM\Domains\Category;
use ArtemsWay\Parser1C\Parsers\DOM\ImportParser;

class ImportParserTest extends TestCase
{
    public function testItCanParseSchemaVersion()
    {
        $parser = new ImportParser;

        $data = $parser->load(self::$importFile)->parseSchemaVersion()->getData();

        $this->assertEquals('2.07', $data['schemaVersion']);
    }

    public function testItCanParseImportTime()
    {
        $parser = new ImportParser;

        $data = $parser->load(self::$importFile)->parseImportTime()->getData();

        $this->assertEquals('2017-05-13T21:41:15', $data['importTime']);
    }

    public function testItCanParseOnlyChanges()
    {
        $parser = new ImportParser;

        $data = $parser->load(self::$importFile)->parseOnlyChanges()->getData();

        $this->assertFalse($data['onlyChanges']);
    }

    public function testItCanParseCategories()
    {
        $parser = new ImportParser;

        $data = $parser->load(self::$importFile)->parseCategories()->getData();

        $this->assertCount(3, $data['categories']);

        $subcategory = array_pop($data['categories']);

        $this->assertInstanceOf(Category::class, $subcategory);

        $this->assertEquals('9c556d51-720f-11df-b436-0015e92f2802', $subcategory->id);
        $this->assertEquals('9c556d50-720f-11df-b436-0015e92f2802', $subcategory->parent);
        $this->assertEquals('Мокасины', $subcategory->name);
    }

    public function testItCanParseProperties()
    {
        $parser = new ImportParser;

        $data = $parser->load(self::$importFile)->parseProperties()->getData();

        $this->assertCount(4, $data['properties']);

        $property = array_shift($data['properties']);

        $this->assertInstanceOf(Property::class, $property);

        $this->assertEquals('0c6013c7-ea5e-11e0-a7cd-e0cb4ed5f6e4', $property->id);
        $this->assertEquals('Число жил', $property->name);
        $this->assertEquals('vocabulary', $property->type);
        $this->assertCount(3, $property->values);
    }

    public function testItCanParseProducts()
    {
        $parser = new ImportParser;

        $data = $parser->load(self::$importFile)->parseProducts()->getData();

        $this->assertCount(9, $data['products']);

        $product = array_shift($data['products']);

        $this->assertInstanceOf(Product::class, $product);

        $this->assertEquals('dee6e1d0-55bc-11d9-848a-00112f43529a', $product->id);
        $this->assertEquals('К-120003', $product->sku);
        $this->assertEquals('Кроссовки "ADIDAS"', $product->name);
        $this->assertCount(3, $product->unit);
        $this->assertEquals('9c556d50-720f-11df-b436-0015e92f2802', $product->category);
        $this->assertEquals('Для теста', $product->description);
        $this->assertCount(1, $product->images);
        $this->assertCount(2, $product->characteristics);
        $this->assertCount(3, $product->requisites);
    }
}
