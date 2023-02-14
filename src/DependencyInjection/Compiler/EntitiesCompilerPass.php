<?php

declare(strict_types=1);

namespace Wata\DwhQueryDoctrineBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EntitiesCompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        $doctrine = $container->get('doctrine.orm.default_entity_manager');

    }

}
