<?php

namespace ArtemsWay\Parser1C\ORM\Mappers\DOM;

use ArtemsWay\Parser1C\ORM\Domains\Property;
use ArtemsWay\Parser1C\Parsers\DOM\DOMXpathHelper;

class PropertyMapper extends DOMMapper
{
    /**
     * Известные типы свойств.
     *
     * @var array
     */
    protected $types = [
        'Справочник' => 'voc',
        'Строка' => 'str',
        'Число' => 'int'
    ];

    /**
     * Парсим объект DOMElement в объект Property.
     *
     * @return Property
     */
    public function map()
    {
        $property = new Property;

        $property->raw = $this->element;

        $property->id = DOMXpathHelper::getString(
            $this->document,
            'c:Ид',
            $this->element,
            true
        );

        $property->name = DOMXpathHelper::getString(
            $this->document,
            'c:Наименование',
            $this->element,
            true
        );

        $property->type = $this->getType();

        if ($property->type === 'voc') {
            $property->values = $this->getValues();
        }

        return $property;
    }

    /**
     * Получаем тип свойства.
     *
     * @return mixed
     */
    protected function getType()
    {
        $type = DOMXpathHelper::getString(
            $this->document,
            'c:ТипЗначений',
            $this->element,
            true
        );

        if (!array_key_exists($type, $this->types)) {
            throw new \InvalidArgumentException(
                "Unknown property type: $type."
            );
        }

        return $this->types[$type];
    }

    /**
     * Получаем все значения справочника.
     *
     * @return array
     */
    protected function getValues()
    {
        $values = [];

        $vocabularies = DOMXpathHelper::evaluate(
            $this->document,
            'c:ВариантыЗначений/c:Справочник',
            $this->element
        );

        foreach ($vocabularies as $vocabulary) {
            $id = DOMXpathHelper::getString(
                $this->document,
                'c:ИдЗначения',
                $vocabulary,
                true
            );

            $values[$id] = DOMXpathHelper::getString(
                $this->document,
                'c:Значение',
                $vocabulary,
                true
            );
        }

        return $values;
    }
}
