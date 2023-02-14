<?php

declare(strict_types=1);

namespace Wata\DwhQueryDoctrineBundle\Factory\Type;

use GraphQL\Type\Definition\ObjectType;
use Wata\DwhQueryDoctrineBundle\Factory\FieldsConfigurationFactory;
use Wata\DwhQueryDoctrineBundle\Utils\Utils;

class EntityTypeFactory extends AbstractTypeFactory
{
    public function getType(bool $followAssociations = true): ObjectType
    {
        $typeName = Utils::getTypeName($this->className);

        $fieldsConfigurationFactory = new FieldsConfigurationFactory($this->className, $this->entityManager);
        $fields = $fieldsConfigurationFactory->create($followAssociations);

        return new ObjectType([
            'name' => $typeName,
            'description' => '',
            'fields' => $fields,
        ]);
    }

}
