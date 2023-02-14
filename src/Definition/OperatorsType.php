<?php

declare(strict_types=1);

namespace Wata\DwhQueryDoctrineBundle\Definition;

use Wata\DwhQueryDoctrineBundle\Definition\Operators\OperatorInterface;
use Wata\DwhQueryDoctrineBundle\Utils\OperatorsService;

class OperatorsType
{
    private array $operators = [];

    public function __construct()
    {
        $operatorsService = OperatorsService::getInstance();

        foreach ($operatorsService->getOperators() as $operator) {
            $this->operators[$operator->getOperator()] = new $operator();
        }
    }

    public function getAllOperators(): array
    {
        return $this->operators;
    }

    public function getOperator(string $operator): OperatorInterface
    {
        return $this->operators[$operator];
    }
}
