<?php

declare(strict_types=1);

namespace Wata\DwhQueryDoctrineBundle\Factory;

use Doctrine\Persistence\ManagerRegistry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Wata\DwhQueryBundle\Resolver\ResolverMapBuilder;
use Wata\DwhQueryDoctrineBundle\Resolver\DoctrineQueryResolver;
use Wata\DwhQueryDoctrineBundle\Utils\Utils;


class EntityTypeRegistry
{
    private ManagerRegistry $managerRegistry;
    private array $doctrineEntities;
    private ResolverMapBuilder $resolverMapBuilder;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(
        ManagerRegistry    $managerRegistry,
        array              $doctrineEntities,
        ResolverMapBuilder $resolverMapBuilder
    )
    {
        $this->managerRegistry = $managerRegistry;
        $this->doctrineEntities = $doctrineEntities;
        $this->resolverMapBuilder = $resolverMapBuilder;
    }

    public function getQueryType(): ObjectType
    {
        return new ObjectType([
            'name' => 'Query',
            'fields' => $this->getRootEntityFields()
        ]);
    }

    /**
     * @return Type[]
     */
    private function getRootEntityFields(): array
    {
        $fields = [];

        foreach ($this->doctrineEntities as $doctrineEntity) {
            $entityTypeName = Utils::getTypeName($doctrineEntity['class']);
            $entityFieldFactory = new RootFieldFactory(
                $doctrineEntity['class'],
                $this->managerRegistry->getManager($doctrineEntity['manager'])
            );
            $fields[$entityTypeName] = $entityFieldFactory->create();
            $this->resolverMapBuilder->addResolverForAlias(
                $entityTypeName,
                new DoctrineQueryResolver(
                    $doctrineEntity['class'],
                    $this->managerRegistry->getManager($doctrineEntity['manager'])
                )
            );
        }

        return $fields;
    }

}
