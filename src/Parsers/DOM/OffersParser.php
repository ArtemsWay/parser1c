<?php

namespace ArtemsWay\Parser1C\Parsers\DOM;

use ArtemsWay\Parser1C\ORM\Mappers\DOM\OfferMapper;
use ArtemsWay\Parser1C\ORM\Mappers\DOM\PriceTypeMapper;
use ArtemsWay\Parser1C\ORM\Mappers\DOM\WarehouseMapper;

class OffersParser extends DOMParser
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
    public $priceTypes = [];

    /**
     * @var array
     */
    public $warehouses = [];

    /**
     * @var array
     */
    public $offers = [];

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
            '//c:ПакетПредложений/@СодержитТолькоИзменения',
            null,
            true
        );

        return $this;
    }

    /**
     * Парсим типы цен.
     *
     * @return $this
     */
    public function parsePriceTypes()
    {
        $types = DOMXpathHelper::evaluate(
            $this->document,
            '//c:ТипыЦен/c:ТипЦены'
        );

        if ($types->length) {
            $this->mapPriceTypes($types);
        }

        return $this;
    }

    /**
     * Парсим склады.
     *
     * @return $this
     */
    public function parseWarehouses()
    {
        $warehouses = DOMXpathHelper::evaluate(
            $this->document,
            '//c:Склады/c:Склад'
        );

        if ($warehouses->length) {
            $this->mapWarehouses($warehouses);
        }

        return $this;
    }

    /**
     * Парсим предложения.
     *
     * @return $this
     */
    public function parseOffers()
    {
        $offers = DOMXpathHelper::evaluate(
            $this->document,
            '//c:Предложения/c:Предложение'
        );

        if ($offers->length) {
            $this->mapOffers($offers);
        }

        return $this;
    }

    /**
     * Мапим типы цен.
     *
     * @param \DOMNodeList $types
     */
    protected function mapPriceTypes(\DOMNodeList $types)
    {
        foreach ($types as $type) {
            $entity = (new PriceTypeMapper($this->document, $type))->map();

            $this->priceTypes[$entity->id] = $entity;
        }
    }

    /**
     * Мапим склады.
     *
     * @param \DOMNodeList $warehouses
     */
    protected function mapWarehouses(\DOMNodeList $warehouses)
    {
        foreach ($warehouses as $warehouse) {
            $entity = (new WarehouseMapper($this->document, $warehouse))->map();

            $this->warehouses[$entity->id] = $entity;
        }
    }

    /**
     * Мапим предложения.
     *
     * @param \DOMNodeList $offers
     */
    protected function mapOffers(\DOMNodeList $offers)
    {
        foreach ($offers as $offer) {
            $entity = (new OfferMapper($this->document, $offer))->map();

            if ($entity->id !== $entity->productId) {
                $this->offers[$entity->productId][] = $entity;
            } else {
                $this->offers[$entity->productId] = $entity;
            }
        }
    }
}
