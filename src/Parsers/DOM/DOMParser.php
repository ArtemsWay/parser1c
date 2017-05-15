<?php

namespace ArtemsWay\Parser1C\Parsers\DOM;

use ArtemsWay\Parser1C\Parsers\ParserInterface;

abstract class DOMParser implements ParserInterface
{
    /**
     * Содержит DOM дерево всего файла.
     *
     * @var \DOMDocument
     */
    public $document;

    /**
     * Загружаем файл в DOM.
     *
     * @param string $file
     * @return $this
     */
    public function load($file)
    {
        libxml_use_internal_errors(true);

        $this->document = new \DOMDocument();

        $this->document->load($file);

        if ($error = libxml_get_last_error()) {
            throw new \RuntimeException(
                "DOMDocument load file error: {$error->message}."
            );
        }

        return $this;
    }

    /**
     * Вызываем все методы с приставкой parse.
     *
     * @return $this
     */
    public function parseAll()
    {
        $methods = get_class_methods($this);

        foreach ($methods as $method) {
            if (strpos($method, 'parse') !== false && $method !== __FUNCTION__) {
                call_user_func([$this, $method]);
            }
        }

        return $this;
    }

    /**
     * Получаем все свойства объекта парсера.
     *
     * @return array
     */
    public function getData()
    {
        return get_object_vars($this);
    }
}
