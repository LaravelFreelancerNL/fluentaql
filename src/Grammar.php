<?php
namespace LaravelFreelancerNL\FluentAQL;

/*
 * Provides AQL syntax functions
 */

use Exception;
use LaravelFreelancerNL\FluentAQL\Exceptions\BindException;
use LaravelFreelancerNL\FluentAQL\Exceptions\ExpressionTypeException;
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

    protected $rangeOperator = '..';

    protected $keywords = [
        'AGGREGATE', 'ALL', 'AND', 'ANY', 'ASC', 'COLLECT', 'DESC', 'DISTINCT', 'FALSE', 'FILTER', 'FOR', 'GRAPH', 'IN',
        'INBOUND', 'INSERT', 'INTO', 'LET', 'LIMIT', 'NONE', 'NOT', 'NULL', 'OR', 'OUTBOUND', 'REMOVE', 'REPLACE',
        'RETURN', 'SHORTEST_PATH', 'SORT', 'TRUE', 'UPDATE', 'UPSERT', 'WITH'
    ];


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
        $bind = false;

        if (is_scalar($data)) {
            $bind = true;
        }
        if ($data instanceof \DateTime) {
            $data = $data->format(\DateTime::ATOM);
            $bind = true;
        }
        if (is_object($data)) {
            $data = json_encode($data, JSON_UNESCAPED_SLASHES);
            $bind = true;
        }
        if (is_array($data)) {
            $data = array_map([$this, 'prepareDataToBind'], $data);
            $bind = true;
        }

        if ($bind) {
            return $data;
        }

        throw new BindException("'Data type is not allowed for a binding (scalar, DateTime, object or array): ".var_export($data, true));
    }

    /**
     * @param $value
     * @return bool
     */
    public function is_bind($value)
    {
        if (is_string($value)) {
            return true;
        }
        if (is_object($value)) {
            return true;
        }
        return false;
    }

    /**
     * @param $value
     * @return bool
     */
    public function is_range($value)
    {
        if (is_string($value) && preg_match('/^[0-9]+(?:\.[0-9]+)?+\.{2}[0-9]+(?:\.[0-9]+)?$/', $value)) {
            return true;
        }
        return false;
    }

    /**
     * @param $value
     * @return bool
     */
    public function is_numeric($value)
    {
        return is_numeric($value);
    }
    
    /**
     * @param $variableName
     * @return bool
     */
    public function is_variable($variableName)
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
    public function is_list($value)
    {
        return is_array($value) && $this->arrayIsNumeric($value);
    }

    /**
     * @param $value
     * @return bool
     */
    public function is_query($value)
    {
        return $value instanceof QueryBuilder;
    }

    public function is_logicalOperator($operator)
    {
        return in_array($operator, $this->logicalOperators);
    }

    public function is_comparisonOperator($operator)
    {
        return in_array($operator, $this->comparisonOperators);
    }

    public function is_sortDirection($value)
    {
        if (preg_match('/asc|desc/i', $value)) {
            return true;
        }
        return false;
    }

    /**
     * @param $attributeName
     * @return bool
     */
    public function validateAttributeNameSyntax($attributeName)
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
    public function is_collection($collectionName)
    {
        if (preg_match('/^[a-zA-Z0-9_-]+$/', $collectionName)) {
            return true;
        }
        return false;
    }

    public function validateBindParameterSyntax($bindParameter)
    {
        if (preg_match('/^@?[a-zA-Z0-9][a-zA-Z0-9_]*$/', $bindParameter)) {
            return true;
        }
        return false;
    }

    public function is_key($value)
    {
        if (preg_match("/^[a-zA-Z0-9_-]+\/?[a-zA-Z0-9_\-\:\.\@\(\)\+\,\=\;\$\!\*\'\%]+$/", $value)) {
            return true;
        }
        return false;
    }

    /**
     * Check if the array is associative
     *
     * @param array $array
     * @return bool
     */
    public function arrayIsAssociative(array $array)
    {
        if (empty($array)) {
            return true;
        }
        return !ctype_digit(implode('', array_keys($array)));
    }

    /**
     * Check if the array is numeric
     *
     * @param array $array
     * @return bool
     */
    public function arrayIsNumeric(array $array)
    {
        if (empty($array)) {
            return true;
        }
        return ctype_digit(implode('', array_keys($array)));
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
        return $prefix.$bindVariableName;
    }
}
