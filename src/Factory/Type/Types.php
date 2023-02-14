<?php

declare(strict_types=1);

namespace Wata\DwhQueryDoctrineBundle\Factory\Type;

use Doctrine\Persistence\ObjectManager;
use GraphQL\Type\Definition\Type;
use Wata\DwhQueryDoctrineBundle\Definition\GroupByType;
use Wata\DwhQueryDoctrineBundle\Definition\SortingOrderType;
use Wata\DwhQueryDoctrineBundle\Definition\WhereClauseType;
use Wata\DwhQueryDoctrineBundle\Utils\Utils;

class Types
{
    private const DEFAULT_TYPE = 'string';

    /** @var Type[] */
    private static array $types;

    public function __construct()
    {
        self::$types = [
            'id' => Type::id(),
            'boolean' => Type::boolean(),
            'int' => Type::int(),
            'float' => Type::float(),
            'string' => Type::string(),
        ];

        $this->addType(new SortingOrderType());
        $this->addType(new WhereClauseType());
        $this->addType(new GroupByType());
    }

    public function getType(string $typeName): Type
    {
        return self::$types[$typeName] ?? self::$types[self::DEFAULT_TYPE];
    }

    public function addType(Type $type): void
    {
        self::$types[$type->name] = $type;
    }

    public function getEntityType(string $className, ObjectManager $entityManager, bool $followAssociations): Type
    {
        $typeName = Utils::getTypeName($className);

        if (isset(self::$types[$typeName])) {
            return self::$types[$typeName];
        }

        $entityTypeFactory = new EntityTypeFactory(
            $className,
            $entityManager
        );

        $type = $entityTypeFactory->getType($followAssociations);

        $this->addType($type);

        return $type;
    }

    public function getSortingType(string $className, ObjectManager $entityManager): Type
    {
        $typeName = Utils::getTypeName($className) . 'Sorting';

        if (isset(self::$types[$typeName])) {
            return self::$types[$typeName];
        }

        $sortingTypeFactory = new SortingTypeFactory(
            $className,
            $entityManager
        );

        $type = $sortingTypeFactory->getType();

        $this->addType($type);

        return $type;
    }

    public function getWhereType(string $className, ObjectManager $entityManager): Type
    {
        $typeName = Utils::getTypeName($className) . 'Where';

        if (isset(self::$types[$typeName])) {
            return self::$types[$typeName];
        }

        $whereTypeFactory = new WhereTypeFactory(
            $className,
            $entityManager
        );

        $type = $whereTypeFactory->getType();

        $this->addType($type);

        return $type;
    }

    public function getGroupByType(string $className, ObjectManager $entityManager): Type
    {
        $typeName = Utils::getTypeName($className) . 'GroupBy';

        if (isset(self::$types[$typeName])) {
            return self::$types[$typeName];
        }

        $groupByTypeFactory = new GroupByTypeFactory(
            $className,
            $entityManager
        );

        $type = $groupByTypeFactory->getType();

        $this->addType($type);

        return $type;
    }
}
