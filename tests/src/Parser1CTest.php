<?php

namespace ArtemsWay\Parser1C\Tests;

use ArtemsWay\Parser1C\Parser1C;
use ArtemsWay\Parser1C\Parsers\DOM\ImportParser;
use ArtemsWay\Parser1C\Parsers\DOM\OffersParser;

class Parser1CTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testItThrowExceptionIfFileDoesNotExists()
    {
        new Parser1C('', new ImportParser);
    }

    public function testItCanReturnParserInstance()
    {
        $parser = (new Parser1C(self::$importFile, new ImportParser))->load();

        $this->assertInstanceOf(ImportParser::class, $parser);
    }

    public function testItCanParseImportFile()
    {
        $parser = (new Parser1C(self::$importFile, new ImportParser))->load();

        $data = $parser->parseAll()->getData();

        $this->assertEquals('2.07', $data['schemaVersion']);
        $this->assertEquals('2017-05-13T21:41:15', $data['importTime']);
        $this->assertFalse($data['onlyChanges']);
        $this->assertNotEmpty($data['categories']);
        $this->assertNotEmpty($data['properties']);
        $this->assertNotEmpty($data['products']);
    }

    public function testItCanParseOffersFile()
    {
        $parser = (new Parser1C(self::$offersFile, new OffersParser))->load();

        $data = $parser->parseAll()->getData();

        $this->assertEquals('2.07', $data['schemaVersion']);
        $this->assertEquals('2017-05-13T21:41:15', $data['importTime']);
        $this->assertFalse($data['onlyChanges']);
        $this->assertNotEmpty($data['priceTypes']);
        $this->assertNotEmpty($data['warehouses']);
        $this->assertNotEmpty($data['offers']);
    }
}
