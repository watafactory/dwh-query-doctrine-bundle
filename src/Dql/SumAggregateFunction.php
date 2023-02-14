<?php

namespace Wata\DwhQueryDoctrineBundle\Dql;

class SumAggregateFunction implements IDqlAggregateFunction
{
    public function getPrefix(): string {
        return 'sum';
    }

    public function getType(): string
    {
        return 'float';
    }
}
