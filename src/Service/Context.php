<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Strategy\StrategyInterface;

class Context
{
    private $strategies = [];

    public function addStrategy(StrategyInterface $strategy)
    {
        $this->strategies[] = $strategy;
    }

    public function handle(string $format)
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->canLoad($format)) {
                return $strategy->load($format);
            }
        }

        throw new \InvalidArgumentException('Strategy could not.');
    }
}