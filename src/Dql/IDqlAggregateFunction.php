<?php

namespace Wata\DwhQueryDoctrineBundle\Dql;

interface IDqlAggregateFunction
{
    public function getPrefix(): string;

    public function getType(): string;
}
