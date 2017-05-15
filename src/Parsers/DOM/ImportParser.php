<?php

namespace ArtemsWay\Parser1C\Parsers\DOM;

use ArtemsWay\Parser1C\ORM\Mappers\DOM\ProductMapper;
use ArtemsWay\Parser1C\ORM\Mappers\DOM\CategoryMapper;
use ArtemsWay\Parser1C\ORM\Mappers\DOM\PropertyMapper;

class ImportParser extends DOMParser
{
    /**
     * @var string
     */
    public $schemaVersion;

    /**
     * @var string
     */
    public $importTime;

    /**
     * @var string
     */
    public $onlyChanges;

    /**
     * @var array
     */
    public $categories = [];

    /**
     * @var array
     */
    public $properties = [];

    /**
     * @var array
     */
    public $products = [];

    /**
     * Получаем версию схемы.
     *
     * @return $this
     */
    public function parseSchemaVersion()
    {
        $this->schemaVersion = DOMXpathHelper::getString(
            $this->document,
            '//c:КоммерческаяИнформация/@ВерсияСхемы',
            null,
            true
        );

        return $this;
    }

    /**
     * Получаем время выгрузки.
     *
     * @return $this
     */
    public function parseImportTime()
    {
        $this->importTime = DOMXpathHelper::getString(
            $this->document,
            '//c:КоммерческаяИнформация/@ДатаФормирования',
            null,
            true
        );

        return $this;
    }

    /**
     * Проверяем содержит ли файл только изменения.
     *
     * @return $this
     */
    public function parseOnlyChanges()
    {
        $this->onlyChanges = DOMXpathHelper::getBoolean(
            $this->document,
            '//c:Каталог/@СодержитТолькоИзменения',
            null,
            true
        );

        return $this;
    }

    /**
     * Парсим категории.
     *
     * @return $this
     */
    public function parseCategories()
    {
        $categories = DOMXpathHelper::evaluate(
            $this->document,
            '//c:Классификатор/c:Группы/c:Группа'
        );

        if ($categories->length) {
            $this->mapCategories($categories);
        }

        return $this;
    }

    /**
     * Парсим свойства.
     *
     * @return $this
     */
    public function parseProperties()
    {
        $properties = DOMXpathHelper::evaluate(
            $this->document,
            '//c:Свойства/c:Свойство'
        );

        if ($properties->length) {
            $this->mapProperties($properties);
        }

        return $this;
    }

    /**
     * Парсим продукты.
     *
     * @return $this
     */
    public function parseProducts()
    {
        $products = DOMXpathHelper::evaluate(
            $this->document,
            '//c:Товары/c:Товар'
        );

        $this->mapProducts($products);

        return $this;
    }

    /**
     * Мапим категории.
     *
     * @param \DOMNodeList $categories
     * @param null|string $parent
     */
    protected function mapCategories(
        \DOMNodeList $categories,
        $parent = null
    ) {
        foreach ($categories as $category) {
            $entity = (new CategoryMapper($this->document, $category))->map();

            $entity->parent = $parent;

            $this->categories[$entity->id] = $entity;

            $children = DOMXpathHelper::evaluate(
                $this->document,
                'c:Группы/c:Группа',
                $category
            );

            if ($children->length) {
                $this->mapCategories($children, $entity->id);
            }
        }
    }

    /**
     * Мапим свойства.
     *
     * @param \DOMNodeList $properties
     */
    protected function mapProperties(\DOMNodeList $properties)
    {
        foreach ($properties as $property) {
            $entity = (new PropertyMapper($this->document, $property))->map();

            $this->properties[$entity->id] = $entity;
        }
    }

    /**
     * Мапим продукты.
     *
     * @param \DOMNodeList $products
     */
    protected function mapProducts(\DOMNodeList $products)
    {
        foreach ($products as $product) {
            $entity = (new ProductMapper($this->document, $product))->map();

            $this->products[$entity->id] = $entity;
        }
    }
}
