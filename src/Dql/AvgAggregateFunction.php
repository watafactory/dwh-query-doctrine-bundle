<?php

namespace Wata\DwhQueryDoctrineBundle\Dql;

class AvgAggregateFunction implements IDqlAggregateFunction
{
    public function getPrefix(): string {
        return 'avg';
    }

    public function getType(): string
    {
        return 'float';
    }
}
