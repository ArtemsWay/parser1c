<?php

namespace ArtemsWay\Parser1C\ORM\Mappers\DOM;

use ArtemsWay\Parser1C\ORM\Domains\Product;
use ArtemsWay\Parser1C\Parsers\DOM\DOMXpathHelper;

class ProductMapper extends DOMMapper
{
    /**
     * Парсим объект DOMElement в объект Product.
     *
     * @return Product
     */
    public function map()
    {
        $product = new Product;

        $product->raw = $this->element;

        $product->id = DOMXpathHelper::getString(
            $this->document,
            'c:Ид',
            $this->element,
            true
        );

        $product->name = DOMXpathHelper::getString(
            $this->document,
            'c:Наименование',
            $this->element,
            true
        );

        $product->description = DOMXpathHelper::getString(
            $this->document,
            'c:Описание',
            $this->element
        );

        $product->sku = DOMXpathHelper::getString(
            $this->document,
            'c:Артикул',
            $this->element
        );

        $product->category = DOMXpathHelper::getString(
            $this->document,
            'c:Группы/c:Ид',
            $this->element
        );

        $product->deleted = $this->isDeleted();

        $product->unit = $this->getUnit();

        $product->images = $this->getImages();

        $product->properties = $this->getProperties();

        $product->characteristics = $this->getCharacteristics();

        $product->requisites = $this->getRequisites();

        return $product;
    }

    /**
     * Проверяем удален ли продукт.
     *
     * @return bool
     */
    protected function isDeleted()
    {
        $status = $this->element->getAttribute('Статус');

        if (!empty($status) && $status == 'Удален') {
            return true;
        }

        return false;
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
     * Получаем картинки.
     *
     * @return array
     */
    protected function getImages()
    {
        $images = DOMXpathHelper::evaluate(
            $this->document,
            'c:Картинка',
            $this->element
        );

        $values = [];

        foreach ($images as $image) {
            $value = DOMXpathHelper::getString(
                $this->document,
                'text()',
                $image
            );

            $values[] = $value;
        }

        return $values;
    }

    /**
     * Получаем все свойства.
     *
     * @return array
     */
    protected function getProperties()
    {
        $properties = DOMXpathHelper::evaluate(
            $this->document,
            'c:ЗначенияСвойств/c:ЗначенияСвойства',
            $this->element
        );

        $values = [];

        foreach ($properties as $property) {
            $id = DOMXpathHelper::getString(
                $this->document,
                'c:Ид',
                $property,
                true
            );

            $value = DOMXpathHelper::getString(
                $this->document,
                'c:Значение',
                $property,
                true
            );

            $values[$id] = compact('id', 'value');
        }

        return $values;
    }

    /**
     * Получаем все характеристики.
     *
     * @return array
     */
    protected function getCharacteristics()
    {
        $characteristics = DOMXpathHelper::evaluate(
            $this->document,
            'c:ХарактеристикиТовара/c:ХарактеристикаТовара',
            $this->element
        );

        $values = [];

        foreach ($characteristics as $characteristic) {
            $id = DOMXpathHelper::getString(
                $this->document,
                'c:Ид',
                $characteristic,
                true
            );

            $name = DOMXpathHelper::getString(
                $this->document,
                'c:Наименование',
                $characteristic,
                true
            );

            $value = DOMXpathHelper::getString(
                $this->document,
                'c:Значение',
                $characteristic,
                true
            );

            $values[$id] = compact('id', 'name', 'value');
        }

        return $values;
    }

    /**
     * Получаем реквизиты.
     *
     * @return array
     */
    protected function getRequisites()
    {
        $requisites = DOMXpathHelper::evaluate(
            $this->document,
            'c:ЗначенияРеквизитов/c:ЗначениеРеквизита',
            $this->element
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
