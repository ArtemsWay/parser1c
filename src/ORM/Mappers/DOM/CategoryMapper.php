<?php

namespace ArtemsWay\Parser1C\ORM\Mappers\DOM;

use ArtemsWay\Parser1C\ORM\Domains\Category;
use ArtemsWay\Parser1C\Parsers\DOM\DOMXpathHelper;

class CategoryMapper extends DOMMapper
{
    /**
     * Парсинг объекта DOMElement в объект Category.
     *
     * @return Category
     */
    public function map()
    {
        $category = new Category;

        $category->raw = $this->element;

        $category->id = DOMXpathHelper::getString(
            $this->document,
            'c:Ид',
            $this->element,
            true
        );

        $category->name = DOMXpathHelper::getString(
            $this->document,
            'c:Наименование',
            $this->element,
            true
        );

        return $category;
    }
}
