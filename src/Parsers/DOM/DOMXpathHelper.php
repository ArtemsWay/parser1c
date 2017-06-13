<?php

namespace ArtemsWay\Parser1C\Parsers\DOM;

class DOMXpathHelper
{
    /**
     * @param $document
     * @param $expression
     * @param \DOMNode|null $contextnode
     * @return mixed
     */
    public static function evaluate($document, $expression, $contextnode = null)
    {
        $xpath = new \DOMXPath($document);

        $uri = $document->documentElement->lookupNamespaceUri(null);

        $xpath->registerNamespace('c', $uri);

        return $xpath->evaluate($expression, $contextnode);
    }

    /**
     * @param $document
     * @param $expression
     * @param \DOMNode|null $contextnode
     * @param bool $strict
     * @return mixed
     */
    public static function getString($document, $expression, $contextnode = null, $strict = false)
    {
        $expression = sprintf('string(%s)', $expression);

        $string = static::evaluate($document, $expression, $contextnode);

        if ($strict && (empty($string) && $string !== '0')) {
            throw new \InvalidArgumentException("Expression [$expression] returned empty string.");
        }

        return $string;
    }

    /**
     * @param $document
     * @param $expression
     * @param \DOMNode|null $contextnode
     * @param bool $strict
     * @return mixed
     */
    public static function getNumber($document, $expression, $contextnode = null, $strict = false)
    {
        $expression = sprintf('number(%s)', $expression);

        $number = static::evaluate($document, $expression, $contextnode);

        if ($strict && is_nan($number)) {
            throw new \InvalidArgumentException("Expression [$expression] returned not a number.");
        }

        return $number;
    }

    /**
     * @param $document
     * @param $expression
     * @param \DOMNode|null $contextnode
     * @param bool $strict
     * @return boolean
     */
    public static function getBoolean($document, $expression, $contextnode = null, $strict = false)
    {
        return filter_var(
            self::getString($document, $expression, $contextnode, $strict),
            FILTER_VALIDATE_BOOLEAN
        );
    }
}
