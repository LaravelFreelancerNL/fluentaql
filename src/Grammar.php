<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL;

use LaravelFreelancerNL\FluentAQL\Traits\ValidatesExpressions;

/**
 * Provides AQL syntax functions
 */
class Grammar
{
    use ValidatesExpressions;

    /**
     * All of the available predicate operators.
     *
     * @var array|int[]
     */
    protected array $comparisonOperators = [
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

    /**
     * @var array|int[]
     */
    protected array $arithmeticOperators = [
        '+' => 1,
        '-' => 1,
        '*' => 1,
        '/' => 1,
        '%' => 1,
    ];

    /**
     * @var array|int[]
     */
    protected array $logicalOperators = [
        'AND' => 1,
        '&&'  => 1,
        'OR'  => 1,
        '||'  => 1,
        'NOT' => 1,
        '!'   => 1,
    ];

    protected string $rangeOperator = '..';

    /**
     * @var string[]
     */
    protected array $keywords = [
        'FOR', 'FILTER', 'SEARCH', 'SORT', 'ASC', 'DESC', 'LIMIT', 'COLLECT', 'INTO', 'AGGREGATE', 'RETURN', 'DISTINCT',
        'WITH', 'GRAPH', 'INBOUND', 'OUTBOUND', 'ANY', 'SHORTEST_PATH', 'K_SHORTEST_PATH', 'PRUNE',
        'LET', 'INSERT', 'UPDATE', 'REPLACE', 'UPSERT', 'REMOVE',
        'ALL', 'NONE', 'AND', 'OR', 'NOT', 'LIKE', 'IN',
        'FALSE', 'TRUE', 'NULL',
    ];

    /**
     * List of recognizable data and the accompanying Expression type it will be mapped too.
     * Strings of an unrecognized nature are always bound.
     *
     * @var array|string[]
     */
    protected array $argumentTypeExpressionMap = [
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

    /**
     * List of default allowed Data Types
     * The order matters in the compilation of the data
     *
     * @var array|string[]
     */
    protected array $defaultAllowedExpressionTypes = [
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

    public function wrap(
        string $value
    ): string {
        return '`' . addcslashes($value, '`') . '`';
    }

    public function mapArgumentTypeToExpressionType(
        string $argumentType
    ): string {
        return $this->argumentTypeExpressionMap[$argumentType];
    }

    public function formatBind(
        string $bindVariableName,
        bool $collection = null
    ): string {
        if (stripos($bindVariableName, '@') === 0) {
            $bindVariableName = $this->wrap($bindVariableName);
        }

        $prefix = '@';
        if ($collection) {
            $prefix = '@@';
        }

        return $prefix . $bindVariableName;
    }

    /**
     * @return array|string[]
     */
    public function getAllowedExpressionTypes(): array
    {
        return $this->defaultAllowedExpressionTypes;
    }
}
