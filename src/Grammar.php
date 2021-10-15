<?php

namespace LaravelFreelancerNL\FluentAQL;

/*
 * Provides AQL syntax functions
 */

use LaravelFreelancerNL\FluentAQL\Traits\ValidatesExpressions;

class Grammar
{
    use ValidatesExpressions;

    /**
     * All of the available predicate operators.
     *
     * @var array
     */
    protected $comparisonOperators = [
        '=='      => 1,
        '!='      => 1,
        '<'       => 1,
        '>'       => 1,
        '<='      => 1,
        '>='      => 1,
        'IN'      => 1,
        'NOT IN'  => 1,
        'LIKE'    => 1,
        '~'       => 1,
        '!~'      => 1,
        'ALL =='  => 1,
        'ALL !='  => 1,
        'ALL <'   => 1,
        'ALL >'   => 1,
        'ALL <='  => 1,
        'ALL >='  => 1,
        'ALL IN'  => 1,
        'ANY =='  => 1,
        'ANY !='  => 1,
        'ANY <'   => 1,
        'ANY >'   => 1,
        'ANY <='  => 1,
        'ANY >='  => 1,
        'ANY IN'  => 1,
        'NONE ==' => 1,
        'NONE !=' => 1,
        'NONE <'  => 1,
        'NONE >'  => 1,
        'NONE <=' => 1,
        'NONE >=' => 1,
        'NONE IN' => 1,
    ];

    protected $arithmeticOperators = [
        '+' => 1,
        '-' => 1,
        '*' => 1,
        '/' => 1,
        '%' => 1,
    ];

    protected $logicalOperators = [
        'AND' => 1,
        '&&'  => 1,
        'OR'  => 1,
        '||'  => 1,
        'NOT' => 1,
        '!'   => 1,
    ];

    protected $rangeOperator = '..';

    protected $keywords = [
        'FOR', 'FILTER', 'SEARCH', 'SORT', 'ASC', 'DESC', 'LIMIT', 'COLLECT', 'INTO', 'AGGREGATE', 'RETURN', 'DISTINCT',
        'WITH', 'GRAPH', 'INBOUND', 'OUTBOUND', 'ANY', 'SHORTEST_PATH', 'K_SHORTEST_PATH', 'PRUNE',
        'LET', 'INSERT', 'UPDATE', 'REPLACE', 'UPSERT', 'REMOVE',
        'ALL', 'NONE', 'AND', 'OR', 'NOT', 'LIKE', 'IN',
        'FALSE', 'TRUE', 'NULL',
    ];

    /*
     * List of recognizable data and the accompanying Expression type it will be mapped too.
     * Strings of an unrecognized nature are always bound.
     */
    protected $argumentTypeExpressionMap = [
        'AssociativeArray'   => 'Object',
        'Attribute'          => 'Literal',
        'Bind'               => 'Bind',
        'CollectionBind'     => 'CollectionBind',
        'Boolean'            => 'Boolean',
        'Collection'         => 'Literal',
        'Constant'           => 'Constant',
        'GraphDirection'     => 'Constant',
        'Document'           => 'Object',
        'Function'           => 'Function',
        'Graph'              => 'String',
        'Id'                 => 'String',
        'IndexedArray'       => 'List',
        'Key'                => 'String',
        'List'               => 'List',
        'Name'               => 'String',
        'Number'             => 'Literal',
        'Null'               => 'Literal',
        'RegisteredVariable' => 'Literal',
        'Variable'           => 'Literal',
        'Reference'          => 'Literal',
        'Object'             => 'Object',
        'Range'              => 'Literal',
        'String'             => 'Bind',
    ];

    /*
     * List of default allowed Data Types
     * The order matters in the compilation of the data
     */
    protected $defaultAllowedExpressionTypes = [
        'Number'    => 'Number',
        'Boolean'   => 'Boolean',
        'Null'      => 'Null',
        'Reference' => 'Reference',
        'Id'        => 'Id',
        'Key'       => 'Key',
        'Bind'      => 'Bind',
    ];

    /**
     * Get the format for database stored dates.
     *
     * @return string
     */
    public function getDateFormat()
    {
        return 'Y-m-d\TH:i:s.v\Z';
    }

    public function wrap($value): string
    {
        return '`' . addcslashes($value, '`') . '`';
    }

    public function mapArgumentTypeToExpressionType($argumentType): string
    {
        return $this->argumentTypeExpressionMap[$argumentType];
    }

    public function formatBind(string $bindVariableName, bool $collection = null)
    {
        if (stripos($bindVariableName, '@') === 0) {
            $bindVariableName = $this->wrap($bindVariableName);
        }

        $prefix = '@';
        if ($collection) {
            $prefix = '@@';
        }

        return $prefix . $bindVariableName;
    }

    public function getAllowedExpressionTypes()
    {
        return $this->defaultAllowedExpressionTypes;
    }
}
