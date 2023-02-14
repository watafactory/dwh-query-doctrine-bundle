<?php

declare(strict_types=1);

namespace Wata\DwhQueryDoctrineBundle\Query;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ObjectManager;
use Wata\DwhQueryDoctrineBundle\Definition\OperatorsType;
use Wata\DwhQueryDoctrineBundle\Utils\DqlFunctionsService;
use Wata\DwhQueryDoctrineBundle\Utils\UniqueParametersFactory;
use Wata\DwhQueryDoctrineBundle\Utils\Utils;

class QueryBuilder
{
    private string $className;
    private ObjectManager $entityManager;
    private ClassMetadata $entityMetadata;
    private UniqueParametersFactory $uniqueParametersFactory;

    public array $dqlFunctions;

    /**
     * @param string $className
     * @param ObjectManager $entityManager
     */
    public function __construct(string $className, ObjectManager $entityManager)
    {
        $this->className = $className;
        $this->entityManager = $entityManager;
        $this->entityMetadata = $entityManager->getMetadataFactory()->getMetadataFor($className);
    }

    public function getQuery(array $wheres, array $orderBy, array $groupBy, array $selectFields): Query
    {
        $this->uniqueParametersFactory = new UniqueParametersFactory();
        $alias = Utils::getTypeName($this->className);
        /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
        $queryBuilder = $this->entityManager->createQueryBuilder();
        if (empty($groupBy)) {
            $queryBuilder->select($alias);
        }
        $queryBuilder->from($this->className, $alias);

        $this->configureJoins($queryBuilder, $alias, $wheres, $orderBy, $groupBy, $selectFields);
        $this->applyFilters($queryBuilder, $wheres, $alias);
        $this->applyOrderBy($queryBuilder, $orderBy, $alias);
        $this->applyGroupBy($queryBuilder, $groupBy, $alias, $selectFields);

        $query = $queryBuilder->getQuery();
        $sql = $query->getSQL();

        return $query;
    }

    private function parseFieldName(string $fieldName, string $alias): string
    {
        if (strpos($fieldName, '__') !== false) {
            $fieldName = str_replace('__', '.', $fieldName);
        } else {
            $fieldName = ucfirst($alias) . '.' . $fieldName;
        }

        return $fieldName;
    }

    private function configureJoins(
        \Doctrine\ORM\QueryBuilder $queryBuilder,
        string $alias,
        array $wheres,
        array $orderBy,
        array $groupBy,
        array $selectFields
    ): void
    {
        foreach ($this->entityMetadata->getAssociationMappings() as $associationMapping) {
            if ($this->checkIfApplyJoin($associationMapping['fieldName'], $wheres, $orderBy, $groupBy, $selectFields)) {
                $queryBuilder->leftJoin(
                    $this->parseFieldName($associationMapping['fieldName'], $alias),
                    $associationMapping['fieldName']
                );
            }
        }
    }

    private function applySelectFields(\Doctrine\ORM\QueryBuilder $queryBuilder, array $fields, string $alias): void
    {
        $selectFields = [];

        foreach ($fields as $field) {
            if (isset($this->entityMetadata->fieldMappings[$field])) {
                $selectFields[] = $this->parseFieldName($field, $alias);
            } else if (isset($this->entityMetadata->associationMappings[$field])) {
                $selectFields[] = $field;
            } else {
                $selectFields[] = $this->setSelectAggregateField($field, $alias);
            }
        }

        $queryBuilder->select($selectFields);
    }

    private function setSelectAggregateField(string $field, string $alias): string
    {
        $dqlFunctionsService = DqlFunctionsService::getInstance();

        foreach ($dqlFunctionsService->getDqlAggregateFunctions() as $aggregateFunctionsClass) {
            $prefix = $aggregateFunctionsClass->getPrefix();
            if (strpos($field, $prefix.'__') === 0) {
                return sprintf($prefix.'(%s) as %s',
                    $this->parseFieldName(str_replace($prefix.'__', '', $field), $alias),
                    $field
                );
            }
        }
    }

    private function applyOrderBy(\Doctrine\ORM\QueryBuilder $queryBuilder, array $orderBy, string $alias): void
    {
        foreach ($orderBy as $field => $orderType) {
            $queryBuilder->addOrderBy($this->parseFieldName($field, $alias), $orderType);
        }
    }

    private function applyFilters(\Doctrine\ORM\QueryBuilder $queryBuilder, array $wheres, string $alias): void
    {
        $operatorsType = new OperatorsType();

        foreach ($wheres as $field => $whereField) {
            $fieldName = $this->parseFieldName($field, $alias);
            foreach ($whereField as $operatorValue => $value) {
                $operator = $operatorsType->getOperator($operatorValue);
                $operator->setDqlFilter($queryBuilder, $fieldName, $value, $this->uniqueParametersFactory);
            }
        }
    }

    private function applyGroupBy(
        \Doctrine\ORM\QueryBuilder $queryBuilder,
        array                      $groupBy,
        string                     $alias,
        array                      $selectFields
    ): void
    {
        if (empty($groupBy)) {
            return;
        }

        foreach ($groupBy as $field) {
            $fieldName = $this->parseFieldName($field, $alias);
            $queryBuilder->addGroupBy($fieldName);
        }

        $this->applySelectFields($queryBuilder, $selectFields, $alias);
    }

    private function checkIfApplyJoin(
        string $fieldName,
        array $wheres,
        array $orderBy,
        array $groupBy,
        array $selectFields
    ): bool
    {
        $parsedSpecialFields = array_map(function ($field) {
            $pieces = explode('__', $field);
            return $pieces[0];
        }, array_merge(array_keys($wheres), $orderBy, $groupBy));

        return
            in_array($fieldName, $parsedSpecialFields, true)
            || in_array($fieldName, $selectFields, true);
    }
}
