<?php

namespace Wata\DwhQueryDoctrineBundle\Dql;

class CountAggregateFunction implements IDqlAggregateFunction
{
    public function getPrefix(): string {
        return 'count';
    }

    public function getType(): string
    {
        return 'int';
    }
}
