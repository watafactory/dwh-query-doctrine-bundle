<?php

declare(strict_types=1);

namespace Wata\DwhQueryDoctrineBundle\Definition;

use GraphQL\Type\Definition\EnumType;

final class SortingOrderType extends EnumType
{
    public function __construct()
    {
        $config = [
            'name' => 'SortingOrder',
            'description' => 'Order to be used in DQL',
            'values' => [
                'ASC',
                'DESC',
            ],
        ];

        parent::__construct($config);
    }
}
