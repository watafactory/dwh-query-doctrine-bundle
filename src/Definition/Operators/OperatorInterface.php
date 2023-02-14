<?php

declare(strict_types=1);

namespace Wata\DwhQueryDoctrineBundle\Definition\Operators;

use Doctrine\ORM\QueryBuilder;
use Wata\DwhQueryDoctrineBundle\Utils\UniqueParametersFactory;

interface OperatorInterface
{
    public function getFieldDescription(): array;

    public function setDqlFilter(
        QueryBuilder            $queryBuilder,
        string                  $fieldName,
                                $value,
        UniqueParametersFactory $uniqueParametersFactory
    );

    public function getOperator(): string;
}
