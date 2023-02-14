<?php

namespace Wata\DwhQueryDoctrineBundle\Factory\Type;

use Doctrine\Persistence\ObjectManager;

abstract class AbstractTypeFactory
{
    protected string $className;
    protected ObjectManager $entityManager;
    protected Types $types;

    /**
     * @param string $className
     * @param ObjectManager $entityManager
     */
    public function __construct(string $className, ObjectManager $entityManager)
    {
        $this->className = $className;
        $this->entityManager = $entityManager;
        $this->types = new Types();
    }

    protected function getFields(): array
    {
        $sortableFields = array_merge(
            $this->getFieldsOfFields($this->className),
            $this->getFieldsOfAssociations($this->className),
        );
        return $sortableFields;
    }

    protected function getFieldsOfFields(string $className, string $prefix = ''): array
    {
        $entityMetadata = $this->entityManager->getMetadataFactory()->getMetadataFor($className);
        $fields = [];
        foreach ($entityMetadata->getFieldNames() as $fieldName) {
            $fields[] = $prefix . $fieldName;
        }
        return $fields;
    }

    protected function getFieldsOfAssociations(string $className): array
    {
        $entityMetadata = $this->entityManager->getMetadataFactory()->getMetadataFor($className);
        $fields = [];
        foreach ($entityMetadata->getAssociationMappings() as $associationMapping) {
            $fields[] = $this->getFieldsOfFields(
                $associationMapping['targetEntity'],
                $associationMapping['fieldName'] . '__'
            );
        }
        $fields = array_merge([], ...$fields);
        return $fields;
    }
}
