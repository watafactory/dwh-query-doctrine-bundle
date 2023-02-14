<?php

namespace Wata\DwhQueryDoctrineBundle\Utils;

class DqlFunctionsService
{
    private iterable $dqlAggregateFunctions;

    /**
     * @param iterable $dqlAggregateFunctions
     */
    public function __construct(iterable $dqlAggregateFunctions)
    {
        $this->dqlAggregateFunctions = $dqlAggregateFunctions;
    }

    /**
     * @return iterable
     */
    public function getDqlAggregateFunctions(): iterable
    {
        return $this->dqlAggregateFunctions;
    }

    public static function getInstance(): DqlFunctionsService
    {
        return $GLOBALS['kernel']->getContainer()->get('dwh_query_doctrine.dql_functions_service');
    }
}
