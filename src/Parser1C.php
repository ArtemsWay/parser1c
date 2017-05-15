<?php

namespace ArtemsWay\Parser1C;

use ArtemsWay\Parser1C\Parsers\ParserInterface;
use ArtemsWay\Parser1C\Parsers\DOM\ImportParser;
use ArtemsWay\Parser1C\Parsers\DOM\OffersParser;

class Parser1C
{
    /**
     * Путь к файлу.
     *
     * @var string
     */
    protected $file;

    /**
     * Объект парсера.
     *
     * @var ParserInterface
     */
    protected $parser;

    public function __construct($file, ParserInterface $parser)
    {
        $this->setFile($file);
        $this->setParser($parser);
    }

    /**
     * Устанавливаем путь к файлу.
     *
     * @param string $file
     */
    public function setFile($file)
    {
        if (!file_exists($file)) {
            throw new \InvalidArgumentException(
                "File does not exists: {$file}"
            );
        }

        $this->file = $file;
    }

    /**
     * Устанавливаем парсер.
     *
     * @param ParserInterface $parser
     */
    public function setParser(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Вызываем метод load у объект парсера
     * и возвращаем объект парсер.
     *
     * @return ParserInterface
     */
    public function load()
    {
        return $this->parser->load($this->file);
    }

    /**
     * Парсим файл используя парсер $parser
     *
     * @param $file
     * @param ParserInterface $parser
     * @return array
     */
    public static function parseFile($file, ParserInterface $parser)
    {
        return (new self($file, $parser))
            ->load()
            ->parseAll()
            ->getData();
    }

    /**
     * Парсим import.xml файл.
     *
     * @param $file
     * @return array
     */
    public static function parseImportFile($file)
    {
        return self::parseFile($file, new ImportParser);
    }

    /**
     * Парсим offers.xml файл.
     *
     * @param $file
     * @return array
     */
    public static function parseOffersFile($file)
    {
        return self::parseFile($file, new OffersParser);
    }
}
