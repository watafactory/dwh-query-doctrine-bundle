<?php

declare(strict_types=1);

namespace Wata\DwhQueryDoctrineBundle\Factory;

use Doctrine\Persistence\ObjectManager;
use GraphQL\Type\Definition\Type;
use Wata\DwhQueryDoctrineBundle\Factory\Type\Types;

class EntityFieldFactory
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

    public function create(bool $isMultiple): array
    {
        $types = new Types();
        $type = $types->getEntityType($this->className, $this->entityManager, false);

        return [
            'type' => $isMultiple ? Type::listOf($type) : $type
        ];
    }
}
