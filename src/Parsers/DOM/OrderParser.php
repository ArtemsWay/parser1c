<?php

namespace ArtemsWay\Parser1C\Parsers\DOM;

use ArtemsWay\Parser1C\ORM\Mappers\DOM\OrderMapper;

class OrderParser extends DOMParser
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
     * @var array
     */
    public $orders = [];

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
     * Парсим предложения.
     *
     * @return $this
     */
    public function parseOrders()
    {
        $orders = DOMXpathHelper::evaluate(
            $this->document,
            '//c:Документ'
        );

        if ($orders->length) {
            $this->mapOrders($orders);
        }

        return $this;
    }

    /**
     * Мапим заказы.
     *
     * @param \DOMNodeList $orders
     */
    protected function mapOrders(\DOMNodeList $orders)
    {
        foreach ($orders as $order) {
            $entity = (new OrderMapper($this->document, $order))->map();

            $this->orders[$entity->id] = $entity;
        }
    }
}
