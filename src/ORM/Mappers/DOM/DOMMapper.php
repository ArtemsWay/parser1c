<?php

namespace ArtemsWay\Parser1C\ORM\Mappers\DOM;

abstract class DOMMapper
{
    /**
     * Содержит все DOM дерево файла.
     *
     * @var \DOMDocument
     */
    protected $document;

    /**
     * @var \DOMElement
     */
    protected $element;

    public function __construct(\DOMDocument $document, \DOMElement $element)
    {
        $this->document = $document;
        $this->element = $element;
    }
}
