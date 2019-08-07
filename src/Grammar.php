<?php
namespace LaravelFreelancerNL\FluentAQL;


/*
 * Provides AQL syntax functions
 */

use Exception;
use LaravelFreelancerNL\FluentAQL\Expressions\ExpressionInterface;
use LaravelFreelancerNL\FluentAQL\Expressions\ListExpression;

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

    public function wrap($value)
    {
        return '`'.addcslashes($value, '`').'`';
    }


    /**
     * @param $data
     * @return array|false|string
     * @throws Exception
     */
    public function prepareDataToBind($data)
    {
        if (is_scalar($data)) {
            return $data;
        }
        if ($data instanceof \DateTime) {
            return $data->format(\DateTime::ATOM);
        }
        if (is_object($data)) {
            return json_encode($data, JSON_UNESCAPED_SLASHES);
        }
        if (is_array($data)) {
            return array_map([$this, 'prepareDataToBind'], $data);
        }

        throw new Exception("Data type is not allowed for a binding: scalar, DateTime, object or array.");
    }

    function normalizeArray($array, $allowedExpressionTypes)
    {
        foreach($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->normalizeArray($value, $allowedExpressionTypes);
            } else {
                $array[$key] = $this->normalizeArgument($value, $allowedExpressionTypes);
            }
        }

        return new ListExpression($array);
    }

    /**
     * @param mixed $argument
     * @param array|string $allowedExpressionTypes
     * @return mixed
     * @throws Exception
     */
    function normalizeArgument($argument, $allowedExpressionTypes)
    {
        if ($argument instanceof ExpressionInterface) {
            return $argument;
        }

        //Check if argument matches $allowedExpressionTypes
        if (is_string($allowedExpressionTypes)) {
            $allowedExpressionTypes = [$allowedExpressionTypes];
        }
        foreach ($allowedExpressionTypes as $allowedExpressionType) {
            $check = 'is_'.$allowedExpressionType;
            if ($this->$check($argument)) {
                $expressionType = $allowedExpressionType;
                break;
            }
        }

        if (! isset($expressionType)) {
            throw new Exception("Not a valid expression type.");
        }

        //Return expression
        $expressionClass = '\LaravelFreelancerNL\FluentAQL\Expressions\\'.ucfirst(strtolower($expressionType)).'Expression';
        return new $expressionClass($argument);
    }

    /**
     * @param $value
     * @return bool
     */
    function is_document($value) {
        if (is_iterable($value)) {
            return false;
        }
        if (is_object($value)) {
            return true;
        }
        //Check for string representation of a JSON object
        if (is_string($value)) {
            return is_object(json_decode($value));
        }
        return false;
    }

    /**
     * @param $value
     * @return bool
     */
    function is_range($value)
    {
        if (is_string($value) && preg_match('/^[0-9]+(?:\.[0-9]+)?+\.{2}[0-9]+(?:\.[0-9]+)?$/', $value) ) {
            return true;
        }
        return false;
    }

    
    /**
     * @param $variableName
     * @return bool
     */
    function is_variable($variableName)
    {
        if (is_string($variableName) && preg_match('/^\$?[a-zA-Z_][a-zA-Z0-9_]*+$/', $variableName)) {
            return true;
        }
        return false;
    }

    /**
     * @param $value
     * @return bool
     */
    function is_list($value)
    {
        return is_iterable($value);
    }

    /**
     * @param $value
     * @return bool
     */
    function is_query($value)
    {
        return $value instanceof QueryBuilder;
    }

    /**
     * @param $value
     * @return bool
     */
    function is_literal($value)
    {
        return is_scalar($value) && ! $this->is_range($value);
    }

    /**
     * @param $attributeName
     * @return bool
     */
    function validateAttributeNameSyntax($attributeName)
    {
        if (preg_match('/^[\p{L}0-9_\-@]+$/u', $attributeName)) {
            return true;
        }
        return false;
    }

    /**
     * @param $collectionName
     * @return bool
     */
    function validateCollectionNameSyntax($collectionName)
    {
        if(preg_match('/^[a-zA-Z0-9_-]+$/', $collectionName)) {
            return true;
        }
        return false;
    }

    function validateBindParameterSyntax($bindParameter)
    {
        if (preg_match('/^@?[a-zA-Z0-9][a-zA-Z0-9_]*$/', $bindParameter)) {
            return true;
        }
        return false;
    }

    function is_key($value)
    {
        if (preg_match("/^[a-zA-Z0-9_-]+\/?[a-zA-Z0-9_\-\:\.\@\(\)\+\,\=\;\$\!\*\'\%]+$/", $value)) {
            return true;
        }
        return false;
    }

}