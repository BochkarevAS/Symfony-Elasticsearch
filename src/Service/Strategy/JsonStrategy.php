<?php

declare(strict_types=1);

namespace App\Service\Strategy;

class JsonStrategy implements StrategyInterface
{
    const JSON = 'json';

    public function canLoad(string $format)
    {
        return self::JSON === $format;
    }

    public function load(string $format)
    {
        return $format;
    }
}