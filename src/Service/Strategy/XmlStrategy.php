<?php

declare(strict_types=1);

namespace App\Service\Strategy;

class XmlStrategy implements StrategyInterface
{
    const XML = 'xml';

    public function canLoad(string $format)
    {
        return self::XML === $format;
    }

    public function load(string $format)
    {
        return $format;
    }
}