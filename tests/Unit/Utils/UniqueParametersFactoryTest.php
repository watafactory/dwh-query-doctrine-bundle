<?php


use Wata\DwhQueryDoctrineBundle\Utils\UniqueParametersFactory;
use PHPUnit\Framework\TestCase;

class UniqueParametersFactoryTest extends TestCase
{
    public function testIfParameterNameIsCreated()
    {
        // GIVEN

        // WHEN
        $uniqueParametersFactory = new UniqueParametersFactory();
        $uniqueParametersFactory->createParameterName();

        // THEN
        $this->assertEquals('filter1', $uniqueParametersFactory->createParameterName());
        $this->assertEquals('filter2', $uniqueParametersFactory->createParameterName());
    }

}
