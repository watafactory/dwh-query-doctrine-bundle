<?php

namespace Wata\DwhQueryDoctrineBundle\Utils;

class OperatorsService
{
    private iterable $operators;

    /**
     * @param iterable $operators
     */
    public function __construct(iterable $operators)
    {
        $this->operators = $operators;
    }

    /**
     * @return iterable
     */
    public function getOperators(): iterable
    {
        return $this->operators;
    }

    public static function getInstance(): OperatorsService
    {
        return $GLOBALS['kernel']->getContainer()->get('dwh_query_doctrine.operators_service');
    }
}
