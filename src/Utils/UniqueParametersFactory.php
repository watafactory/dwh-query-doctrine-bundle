<?php

namespace Wata\DwhQueryDoctrineBundle\Utils;

class UniqueParametersFactory
{
    private int $parameterCount = 0;

    public function createParameterName(): string
    {
        return 'filter' . $this->parameterCount++;
    }

}
