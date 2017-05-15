<?php

namespace ArtemsWay\Parser1C\ORM\Mappers\DOM;

use ArtemsWay\Parser1C\ORM\Domains\Offer;
use ArtemsWay\Parser1C\Parsers\DOM\DOMXpathHelper;

class OfferMapper extends DOMMapper
{
    /**
     * Парсинг объекта DOMElement в объект Offer.
     *
     * @return Offer
     */
    public function map()
    {
        $offer = new Offer;

        $offer->raw = $this->element;

        $offer->id = DOMXpathHelper::getString(
            $this->document,
            'c:Ид',
            $this->element,
            true
        );

        // Вытаскиваем Ид товара
        $offer->productId = strtok($offer->id, '#');

        // Вытаскиваем Ид характеристики если есть. Если нету подставляем Ид товара.
        $offer->characteristicId = strtok('#') ?: $offer->productId;

        $offer->sku = DOMXpathHelper::getString(
            $this->document,
            'c:Артикул',
            $this->element
        );

        $offer->name = DOMXpathHelper::getString(
            $this->document,
            'c:Наименование',
            $this->element,
            true
        );

        $offer->unit = $this->getUnit();

        $offer->prices = $this->getPrices();

        $offer->count = DOMXpathHelper::getNumber(
            $this->document,
            'c:Количество',
            $this->element
        );

        $offer->warehouses = $this->getWarehouses();

        return $offer;
    }

    /**
     * Получаем базовую еденицу.
     *
     * @return array
     */
    protected function getUnit()
    {
        $code = DOMXpathHelper::getString(
            $this->document,
            'c:БазоваяЕдиница/@Код',
            $this->element
        );

        $name = DOMXpathHelper::getString(
            $this->document,
            'c:БазоваяЕдиница/@НаименованиеПолное',
            $this->element
        );

        $reduction = DOMXpathHelper::getString(
            $this->document,
            'c:БазоваяЕдиница/@МеждународноеСокращение',
            $this->element
        );

        return compact('code', 'name', 'reduction');
    }

    /**
     * Получаем цены.
     *
     * @return array
     */
    protected function getPrices()
    {
        $prices = DOMXpathHelper::evaluate(
            $this->document,
            'c:Цены/c:Цена',
            $this->element
        );

        $values = [];

        foreach ($prices as $price) {
            $representation = DOMXpathHelper::getString(
                $this->document,
                'c:Представление',
                $price,
                true
            );

            $id = DOMXpathHelper::getString(
                $this->document,
                'c:ИдТипаЦены',
                $price,
                true
            );

            $pricePerUnite = DOMXpathHelper::getString(
                $this->document,
                'c:ЦенаЗаЕдиницу',
                $price,
                true
            );

            $currency = DOMXpathHelper::getString(
                $this->document,
                'c:Валюта',
                $price,
                true
            );

            $unit = DOMXpathHelper::getString(
                $this->document,
                'c:Единица',
                $price,
                true
            );

            $ratio = DOMXpathHelper::getString(
                $this->document,
                'c:Коэффициент',
                $price,
                true
            );

            $values[$id] = compact(
                'representation',
                'id',
                'pricePerUnite',
                'currency',
                'unit',
                'ratio'
            );
        }

        return $values;
    }

    /**
     * Получаем склады.
     *
     * @return array
     */
    protected function getWarehouses()
    {
        $warehouses = DOMXpathHelper::evaluate(
            $this->document,
            'c:Склад',
            $this->element
        );

        $values = [];

        foreach ($warehouses as $warehouse) {
            $id = $warehouse->getAttribute('ИдСклада');
            $value = $warehouse->getAttribute('КоличествоНаСкладе');

            $values[$id] = compact('id', 'value');
        }

        return $values;
    }
}
