<?php

declare(strict_types=1);

namespace Wata\DwhQueryDoctrineBundle\Definition\Operators;

use Doctrine\ORM\QueryBuilder;
use GraphQL\Type\Definition\Type;
use Wata\DwhQueryDoctrineBundle\Utils\UniqueParametersFactory;

class InOperator implements OperatorInterface
{
    public function getFieldDescription(): array
    {
        return [
            'type' => Type::listOf(Type::string())
        ];
    }

    public function setDqlFilter(
        QueryBuilder            $queryBuilder,
        string                  $fieldName,
                                $value,
        UniqueParametersFactory $uniqueParametersFactory
    )
    {
        $paramName = $uniqueParametersFactory->createParameterName();
        $queryBuilder->setParameter($paramName, $value);

        $queryBuilder->andWhere(sprintf(
            '%s in (:%s)',
            $fieldName,
            $paramName
        ));
    }

    public function getOperator(): string
    {
        return 'in';
    }

}
