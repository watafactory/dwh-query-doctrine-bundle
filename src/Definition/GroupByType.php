<?php

declare(strict_types=1);

namespace Wata\DwhQueryDoctrineBundle\Definition;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\Type;

final class GroupByType extends ListOfType
{
    public function __construct()
    {
        $this->name = 'GroupBy';
        parent::__construct(Type::string());
    }
}
