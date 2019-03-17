<?php

declare(strict_types=1);

namespace App\Service\Strategy;

interface StrategyInterface
{
    public function canLoad(string $format);

    public function load(string $format);
}