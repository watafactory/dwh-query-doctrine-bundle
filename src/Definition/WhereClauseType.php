<?php

namespace Wata\DwhQueryDoctrineBundle\Definition;

use GraphQL\Type\Definition\InputObjectType;

class WhereClauseType extends InputObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'WhereClause',
            'fields' => $this->getOperatorFields(),
        ];

        parent::__construct($config);
    }

    private function getOperatorFields(): array
    {
        $operatorFields = [];
        $operatorsType = new OperatorsType();

        foreach ($operatorsType->getAllOperators() as $operator => $config) {
            $operatorFields[$operator] = $config->getFieldDescription();
        }

        return $operatorFields;
    }
}
