<?php
namespace LaravelFreelancerNL\FluentAQL;


/*
 * Provides AQL syntax functions
 */
class Grammar
{
    /**
     * All of the available predicate operators.
     *
     * @var array
     */
    protected $comparisonOperators = [
        '==', '!=', '<', '>', '<=', '>=', 'IN', 'NOT IN', 'LIKE', '~', '!~',
        'ALL ==', 'ALL !=', 'ALL <', 'ALL >', 'ALL <=', 'ALL >=', 'ALL IN',
        'ANY ==', 'ANY !=', 'ANY <', 'ANY >', 'ANY <=', 'ANY >=', 'ANY IN',
        'NONE ==', 'NONE !=', 'NONE <', 'NONE >', 'NONE <=', 'NONE >=', 'NONE IN'
    ];

    protected $arithmeticOperators = [
        '+', '-', '*', '/', '%'
    ];

    protected $logicalOperators = [
        'AND', '&&', 'OR', '||', 'NOT', '!'
    ];

    protected $keywords = [
        'AGGREGATE', 'ALL', 'AND', 'ANY', 'ASC', 'COLLECT', 'DESC', 'DISTINCT', 'FALSE', 'FILTER', 'FOR', 'GRAPH', 'IN',
        'INBOUND', 'INSERT', 'INTO', 'LET', 'LIMIT', 'NONE', 'NOT', 'NULL', 'OR', 'OUTBOUND', 'REMOVE', 'REPLACE',
        'RETURN', 'SHORTEST_PATH', 'SORT', 'TRUE', 'UPDATE', 'UPSERT', 'WITH'
    ];

    protected $rangeOperator = '..';

    function getDataType($value)
    {
        if ($this->is_document($value) ) {
            return 'document';
        }
        if (is_array($value)) {
            return 'list';
        }
        if ($this->is_range($value) ) {
            return 'list';
        }
        return 'literal';
    }

    function is_document($value) {
        if (is_object($value)) {
            return true;
        }
        if (is_string($value)) {
            $value = trim($value);
            if (stripos(trim($value), '{') === 0 && stripos($value, '}') === strlen($value)-1) {
                return true;
            }
        }
        return false;
    }

    function is_range($value)
    {
        if (preg_match('/^[0-9]+(?:\.[0-9]+)?+\.{2}[0-9]+(?:\.[0-9]+)?$/', $value) ) {
            return true;
        }
        return false;
    }

    function checkVariableNameSyntax($variableName)
    {
        if (preg_match('/^\$?[a-zA-Z_][a-zA-Z0-9_]+$/', $variableName)) {
            return true;
        }
        return false;
    }

    function checkAttributeNameSyntax($attributeName)
    {
        if (preg_match('/^[\p{L}0-9_\-@]+$/u', $attributeName)) {
            return true;
        }
        return false;
    }

    function checkCollectionNameSyntax($collectionName)
    {
        if(preg_match('/^[a-zA-Z0-9_-]+$/', $collectionName)) {
            return true;
        }
        return false;
    }
}