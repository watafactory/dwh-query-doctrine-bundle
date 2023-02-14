<?php

declare(strict_types=1);

namespace Wata\DwhQueryDoctrineBundle\Schema;

use GraphQL\Language\Parser;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use GraphQL\Utils\SchemaExtender;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Wata\DwhQueryBundle\DependencyInjection\Compiler\TypesCompilerPass;
use Wata\DwhQueryBundle\Schema\SchemaBuilderInterface;
use Wata\DwhQueryDoctrineBundle\Factory\EntityTypeRegistry;
use Symfony\Component\DependencyInjection\Container;

class DoctrineSchemaMergerBuilder implements SchemaBuilderInterface
{
    private EntityTypeRegistry $entityTypeRegistry;

    private ?string $manualSchema;

    /**
     * @param EntityTypeRegistry $entityTypeRegistry
     * @param string|null $manualSchema
     */
    public function __construct(EntityTypeRegistry $entityTypeRegistry, ?string $manualSchema)
    {
        $this->entityTypeRegistry = $entityTypeRegistry;
        $this->manualSchema = $manualSchema;
    }

    public function build(): Schema
    {
        $config = SchemaConfig::create()
            ->setQuery($this->entityTypeRegistry->getQueryType());

        $schema = new Schema($config);

        $ast = Parser::parse($this->manualSchema);
        return SchemaExtender::extend($schema, $ast);
    }
}
