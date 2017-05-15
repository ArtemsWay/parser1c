<?php

namespace ArtemsWay\Parser1C\ORM\Mappers\DOM;

use ArtemsWay\Parser1C\ORM\Domains\PriceType;
use ArtemsWay\Parser1C\Parsers\DOM\DOMXpathHelper;

class PriceTypeMapper extends DOMMapper
{
    /**
     * Парсим объект DOMElement в объект PriceType.
     *
     * @return PriceType
     */
    public function map()
    {
        $type = new PriceType;

        $type->raw = $this->element;

        $type->id = DOMXpathHelper::getString(
            $this->document,
            'c:Ид',
            $this->element,
            true
        );

        $type->name = DOMXpathHelper::getString(
            $this->document,
            'c:Наименование',
            $this->element,
            true
        );

        $type->currency = DOMXpathHelper::getString(
            $this->document,
            'c:Валюта',
            $this->element,
            true
        );

        $type->taxes = $this->getTax();

        return $type;
    }

    /**
     * Получаем налоги.
     *
     * @return array
     */
    protected function getTax()
    {
        $taxes = DOMXpathHelper::evaluate(
            $this->document,
            'c:Налог',
            $this->element
        );

        $values = [];

        foreach ($taxes as $tax) {
            $name = DOMXpathHelper::getString(
                $this->document,
                'c:Наименование',
                $tax,
                true
            );

            $included = DOMXpathHelper::getBoolean(
                $this->document,
                'c:УчтеноВСумме',
                $tax,
                true
            );

            $excise = DOMXpathHelper::getBoolean(
                $this->document,
                'c:Акциз',
                $tax,
                true
            );

            $values[] = compact('name', 'included', 'excise');
        }

        return $values;
    }
}
