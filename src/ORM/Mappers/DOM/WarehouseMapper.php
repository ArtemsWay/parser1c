<?php

namespace ArtemsWay\Parser1C\ORM\Mappers\DOM;

use ArtemsWay\Parser1C\ORM\Domains\Warehouse;
use ArtemsWay\Parser1C\Parsers\DOM\DOMXpathHelper;

class WarehouseMapper extends DOMMapper
{
    /**
     * Парсим объект DOMElement в объект Warehouse.
     *
     * @return Warehouse
     */
    public function map()
    {
        $warehouse = new Warehouse;

        $warehouse->raw = $this->element;

        $warehouse->id = DOMXpathHelper::getString(
            $this->document,
            'c:Ид',
            $this->element,
            true
        );

        $warehouse->name = DOMXpathHelper::getString(
            $this->document,
            'c:Наименование',
            $this->element,
            true
        );

        $warehouse->address = $this->getAddress();

        $warehouse->contacts = $this->getContacts();

        return $warehouse;
    }

    /**
     * Получаем адрес склада.
     *
     * @return array
     */
    protected function getAddress()
    {
        $address = [];

        $result = DOMXpathHelper::evaluate(
            $this->document,
            'c:Адрес',
            $this->element
        );

        if (!$result->length) {
            return $address;
        }

        $address['representation'] = DOMXpathHelper::getString(
            $this->document,
            'c:Адрес/c:Представление',
            $this->element
        );

        $fields = DOMXpathHelper::evaluate(
            $this->document,
            'c:Адрес/c:АдресноеПоле',
            $this->element
        );

        if (!$fields->length) {
            return $address;
        }

        $address['fields'] = [];

        foreach ($fields as $field) {
            $type = DOMXpathHelper::getString(
                $this->document,
                'c:Тип',
                $field,
                true
            );

            $value = DOMXpathHelper::getString(
                $this->document,
                'c:Значение',
                $field,
                true
            );

            $address['fields'][] = compact('type', 'value');
        }

        return $address;
    }

    /**
     * Получаем контакты склада.
     *
     * @return array
     */
    protected function getContacts()
    {
        $contacts = DOMXpathHelper::evaluate(
            $this->document,
            'c:Контакты/c:Контакт',
            $this->element
        );

        $values = [];

        foreach ($contacts as $contact) {
            $type = DOMXpathHelper::getString(
                $this->document,
                'c:Тип',
                $contact,
                true
            );

            $value = DOMXpathHelper::getString(
                $this->document,
                'c:Значение',
                $contact,
                true
            );

            $values[] = compact('type', 'value');
        }

        return $values;
    }
}
