<?php

namespace ArtemsWay\Parser1C\ORM\Mappers\DOM;

use ArtemsWay\Parser1C\ORM\Domains\Order;
use ArtemsWay\Parser1C\Parsers\DOM\DOMXpathHelper;

class OrderMapper extends DOMMapper
{
    /**
     * Парсинг объекта DOMElement в объект Order.
     *
     * @return Order
     */
    public function map()
    {
        $order = new Order;

        $order->raw = $this->element;

        $order->id = DOMXpathHelper::getString(
            $this->document,
            'c:Ид',
            $this->element,
            true
        );

        $order->number = DOMXpathHelper::getNumber(
            $this->document,
            'c:Номер',
            $this->element,
            true
        );

        $order->price = DOMXpathHelper::getString(
            $this->document,
            'c:Сумма',
            $this->element,
            true
        );

        $order->date = DOMXpathHelper::getString(
            $this->document,
            'c:Дата',
            $this->element,
            true
        );

        $order->time = DOMXpathHelper::getString(
            $this->document,
            'c:Время',
            $this->element,
            true
        );

        $order->comment = DOMXpathHelper::getString(
            $this->document,
            'c:Комментарий',
            $this->element
        );

        $order->products = $this->getProducts();

        $order->requisites = $this->getRequisites($this->element);

        return $order;
    }

    /**
     * @return array
     */
    protected function getProducts()
    {
        $products = DOMXpathHelper::evaluate(
            $this->document,
            'c:Товары/c:Товар',
            $this->element
        );

        $values = [];

        foreach ($products as $product) {
            $id = DOMXpathHelper::getString(
                $this->document,
                'c:Ид',
                $product,
                true
            );

            $sku = DOMXpathHelper::getString(
                $this->document,
                'c:Артикул',
                $product
            );

            $name = DOMXpathHelper::getString(
                $this->document,
                'c:Наименование',
                $product,
                true
            );

            $price = DOMXpathHelper::getString(
                $this->document,
                'c:ЦенаЗаЕдиницу',
                $product
            );

            $count = DOMXpathHelper::getString(
                $this->document,
                'c:Количество',
                $product
            );

            $total = DOMXpathHelper::getString(
                $this->document,
                'c:Сумма',
                $product
            );

            $requisites = $this->getRequisites($product);

            $values[$id] = compact(
                'id',
                'sku',
                'name',
                'requisites',
                'price',
                'count',
                'total'
            );
        }

        return $values;
    }

    /**
     * @return array
     */
    protected function getRequisites($element)
    {
        $requisites = DOMXpathHelper::evaluate(
            $this->document,
            'c:ЗначенияРеквизитов/c:ЗначениеРеквизита',
            $element
        );

        $values = [];

        foreach ($requisites as $requisite) {
            $name = DOMXpathHelper::getString(
                $this->document,
                'c:Наименование',
                $requisite,
                true
            );

            $value = DOMXpathHelper::getString(
                $this->document,
                'c:Значение',
                $requisite,
                true
            );

            $values[] = compact('name', 'value');
        }

        return $values;
    }
}
