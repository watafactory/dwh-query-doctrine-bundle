<?php

declare(strict_types=1);

namespace Wata\DwhQueryDoctrineBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const NAME = 'dwh_query_doctrine';

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(self::NAME);
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->arrayNode('doctrine_entities')
            ->defaultValue([])
            ->prototype('array')
            ->children()
            ->scalarNode('class')->isRequired()->end()
            ->scalarNode('manager')->defaultValue('default')->end()
            ->end()
            ->end();

        return $treeBuilder;
    }

}
