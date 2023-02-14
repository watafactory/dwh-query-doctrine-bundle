<?php

declare(strict_types=1);

namespace Wata\DwhQueryDoctrineBundle\Factory;

use Doctrine\Persistence\ObjectManager;
use GraphQL\Type\Definition\Type;
use Wata\DwhQueryDoctrineBundle\Factory\Type\Types;

class RootFieldFactory
{
    private string $className;
    private ObjectManager $entityManager;

    /**
     * @param string $className
     * @param ObjectManager $entityManager
     */
    public function __construct(string $className, ObjectManager $entityManager)
    {
        $this->className = $className;
        $this->entityManager = $entityManager;
    }

    public function create(): array
    {
        $types = new Types();
        $type = $types->getEntityType($this->className, $this->entityManager, true);

        return [
            'type' => Type::listOf($type),
            'args' => [
                'where' => $types->getWhereType($this->className, $this->entityManager),
                'orderBy' => $types->getSortingType($this->className, $this->entityManager),
                'groupBy' => $types->getGroupByType($this->className, $this->entityManager)
            ]
        ];
    }
}
