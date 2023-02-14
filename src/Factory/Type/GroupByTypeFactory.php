<?php

declare(strict_types=1);

namespace Wata\DwhQueryDoctrineBundle\Factory\Type;

use GraphQL\Type\Definition\Type;

class GroupByTypeFactory extends AbstractTypeFactory
{
    public function getType(bool $followAssociations = true): Type
    {
        $type = $this->types->getType('GroupBy');

        return $type;
    }


}
