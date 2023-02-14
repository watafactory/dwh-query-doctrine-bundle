<?php

declare(strict_types=1);

namespace Wata\DwhQueryDoctrineBundle\Resolver;

use Doctrine\Persistence\ObjectManager;
use GraphQL\Type\Definition\ResolveInfo;
use Wata\DwhQueryDoctrineBundle\Query\QueryBuilder;

class DoctrineQueryResolver
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

    public function __invoke(array $where, array $orderBy, array $groupBy, array $args, ResolveInfo $info)
    {
        $queryBuilder = new QueryBuilder($this->className, $this->entityManager);
        $query = $queryBuilder->getQuery($where, $orderBy, $groupBy, array_keys($info->getFieldSelection()));

        $result = $query->getResult();
        return $result;
    }
}
