<?php

declare(strict_types=1);

namespace Wata\DwhQueryDoctrineBundle\Factory;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Inflector\EnglishInflector;
use Wata\DwhQueryDoctrineBundle\Factory\Type\Types;
use Wata\DwhQueryDoctrineBundle\Utils\DqlFunctionsService;

class FieldsConfigurationFactory
{
    private ObjectManager $entityManager;
    private ClassMetadata $entityMetadata;

    /**
     * @param string $className
     * @param ObjectManager $entityManager
     */
    public function __construct(string $className, ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->entityMetadata = $entityManager->getMetadataFactory()->getMetadataFor($className);
    }

    public function create(bool $followAssociations = true): array
    {
        return array_merge(
            $this->getFieldsConfiguration(),
            $this->getAggregateFieldsConfiguration(),
            $followAssociations ? $this->getAssociationsConfiguration() : []
        );
    }

    private function getFieldsConfiguration(): array
    {
        $fields = [];
        $types = new Types();

        foreach ($this->entityMetadata->getFieldNames() as $fieldName) {
            $fieldMapping = $this->entityMetadata->getFieldMapping($fieldName);
            $fields[$fieldName] = [
                'type' => $types->getType($fieldMapping['type'])
            ];
        }

        return $fields;
    }

    private function getAggregateFieldsConfiguration(): array
    {
        $fields = [];
        $types = new Types();

        foreach ($this->entityMetadata->getFieldNames() as $fieldName) {

            $dqlFunctionsService = DqlFunctionsService::getInstance();

            foreach ($dqlFunctionsService->getDqlAggregateFunctions() as $aggregateFunctionsClass) {
                $prefix = $aggregateFunctionsClass->getPrefix();
                $type = $aggregateFunctionsClass->getType();

                $fields[$prefix.'__' . $fieldName] = [
                    'type' => $types->getType($type)
                ];
            }

//            $fields['count__' . $fieldName] = [
//                'type' => $types->getType('int')
//            ];
//            $fields['sum__' . $fieldName] = [
//                'type' => $types->getType('float')
//            ];
//            $fields['avg__' . $fieldName] = [
//                'type' => $types->getType('float')
//            ];
//            $fields['stddev_pop__' . $fieldName] = [
//                'type' => $types->getType('float')
//            ];
        }

        return $fields;
    }

    private function getAssociationsConfiguration(): array
    {
        $associations = [];
        $inflector = new EnglishInflector();

        foreach ($this->entityMetadata->getAssociationMappings() as $associationMapping) {
            $entityFieldFactory = new EntityFieldFactory(
                $associationMapping['targetEntity'],
                $this->entityManager
            );
            switch ($associationMapping['type']) {
                case ClassMetadataInfo::MANY_TO_ONE:
                case ClassMetadataInfo::ONE_TO_ONE:
                    $associations[$associationMapping['fieldName']] = $entityFieldFactory->create(false);
                    break;
                case ClassMetadataInfo::MANY_TO_MANY:
                case ClassMetadataInfo::ONE_TO_MANY:
                    $associations[$associationMapping['fieldName']] = $entityFieldFactory->create(true);
                    break;
            }
        }

        return $associations;
    }

}
