<?php

declare(strict_types=1);

namespace Wata\DwhQueryDoctrineBundle;

use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Wata\DwhQueryDoctrineBundle\DependencyInjection\Compiler\EntitiesCompilerPass;

class DwhQueryDoctrineBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new EntitiesCompilerPass(), PassConfig::TYPE_AFTER_REMOVING, 100);
    }

}
