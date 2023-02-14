<?php

declare(strict_types=1);

namespace Wata\DwhQueryDoctrineBundle\Factory\Type;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use Wata\DwhQueryDoctrineBundle\Utils\Utils;

class WhereTypeFactory extends AbstractTypeFactory
{
    public function getType(bool $followAssociations = true): Type
    {

        $typeName = Utils::getTypeName($this->className) . 'Where';

        $type = new InputObjectType([
            'name' => $typeName,
            'fields' => function (): array {
                $fields = [];

                foreach ($this->getFields() as $sortableField) {
                    $fields[] = [
                        'name' => $sortableField,
                        'type' => $this->types->getType('WhereClause')
                    ];
                }
                return $fields;
            },
        ]);

        return $type;
    }


}
