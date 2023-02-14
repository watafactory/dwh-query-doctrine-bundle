<?php

declare(strict_types=1);

namespace Wata\DwhQueryDoctrineBundle\Schema;

use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use Wata\DwhQueryBundle\Schema\SchemaBuilderInterface;
use Wata\DwhQueryDoctrineBundle\Factory\EntityTypeRegistry;

class DoctrineSchemaBuilder implements SchemaBuilderInterface
{
    private EntityTypeRegistry $entityTypeRegistry;

    /**
     * @param EntityTypeRegistry $entityTypeRegistry
     */
    public function __construct(EntityTypeRegistry $entityTypeRegistry)
    {
        $this->entityTypeRegistry = $entityTypeRegistry;
    }

    public function build(): Schema
    {
        $config = SchemaConfig::create()
            ->setQuery($this->entityTypeRegistry->getQueryType());

        $schema = new Schema($config);

        return $schema;
    }

}
