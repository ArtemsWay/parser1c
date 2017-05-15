<?php

namespace ArtemsWay\Parser1C\Parsers;

interface ParserInterface
{
    /**
     * Загрузка файла в DOM.
     *
     * @param $file
     * @return ParserInterface
     */
    public function load($file);

    /**
     * Парсинг всех данных.
     *
     * @return ParserInterface
     */
    public function parseAll();

    /**
     * Получение всех данных.
     *
     * @return array
     */
    public function getData();
}
