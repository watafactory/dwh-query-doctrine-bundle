<?php

declare(strict_types=1);

namespace Wata\DwhQueryDoctrineBundle\Definition\Operators;

use Doctrine\ORM\QueryBuilder;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use Wata\DwhQueryDoctrineBundle\Utils\UniqueParametersFactory;

class BetweenOperator implements OperatorInterface
{
    public function getFieldDescription(): array
    {
        return [
            'type' => new InputObjectType([
                'name' => 'betweenOperator',
                'fields' => [
                    'from' => Type::string(),
                    'to' => Type::string()
                ]
            ])
        ];
    }

    public function setDqlFilter(
        QueryBuilder            $queryBuilder,
        string                  $fieldName,
                                $value,
        UniqueParametersFactory $uniqueParametersFactory
    )
    {
        $paramFromName = $uniqueParametersFactory->createParameterName();
        $paramToName = $uniqueParametersFactory->createParameterName();
        $queryBuilder->setParameter($paramFromName, $value['from']);
        $queryBuilder->setParameter($paramToName, $value['to']);

        $queryBuilder->andWhere(sprintf(
            '%s BETWEEN :%s AND :%s',
            $fieldName,
            $paramFromName,
            $paramToName
        ));
    }

    public function getOperator(): string
    {
        return 'between';
    }

}
