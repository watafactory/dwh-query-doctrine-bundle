<?php


use GraphQL\Type\Schema;
use Wata\DwhQueryDoctrineBundle\Factory\EntityTypeRegistry;
use Wata\DwhQueryDoctrineBundle\Schema\DoctrineSchemaBuilder;
use PHPUnit\Framework\TestCase;

class DoctrineSchemaBuilderTest extends TestCase
{
    public function testIfSchemaIsBuiltSuccessfully()
    {
        // GIVEN
        $entityTypeRegister = $this->getMockBuilder(EntityTypeRegistry::class)
            ->disableOriginalConstructor()->getMock();

        // WHEN
        $schemaBuilder = new DoctrineSchemaBuilder($entityTypeRegister);
        $schema = $schemaBuilder->build();

        // THEN
        $this->assertInstanceOf(Schema::class, $schema);
    }

}
