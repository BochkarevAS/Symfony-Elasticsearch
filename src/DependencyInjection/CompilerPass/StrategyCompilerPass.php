<?php

declare(strict_types=1);

namespace App\DependencyInjection\CompilerPass;

use App\Service\Context;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class StrategyCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(Context::class)) {
            return;
        }

        $contextDefinition = $container->findDefinition('context');

        $strategyServiceIds = array_keys(
            $container->findTaggedServiceIds('strategy')
        );

        foreach ($strategyServiceIds as $strategyServiceId) {
            $contextDefinition->addMethodCall('addStrategy', [new Reference($strategyServiceId)]);
        }
    }
}