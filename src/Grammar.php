<?php
namespace LaravelFreelancerNL\FluentAQL;

/*
 * Provides AQL syntax functions
 */

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;

class Grammar
{
    /**
     * All of the available predicate operators.
     *
     * @var array
     */
    protected $comparisonOperators = [
        '==' => 1,
        '!=' => 1,
        '<' => 1,
        '>' => 1,
        '<=' => 1,
        '>=' => 1,
        'IN' => 1,
        'NOT IN' => 1,
        'LIKE' => 1,
        '~' => 1,
        '!~' => 1,
        'ALL ==' => 1,
        'ALL !=' => 1,
        'ALL <' => 1,
        'ALL >' => 1,
        'ALL <=' => 1,
        'ALL >=' => 1,
        'ALL IN' => 1,
        'ANY ==' => 1,
        'ANY !=' => 1,
        'ANY <' => 1,
        'ANY >' => 1,
        'ANY <=' => 1,
        'ANY >=' => 1,
        'ANY IN' => 1,
        'NONE ==' => 1,
        'NONE !=' => 1,
        'NONE <' => 1,
        'NONE >' => 1,
        'NONE <=' => 1,
        'NONE >=' => 1,
        'NONE IN' => 1
    ];

    protected $arithmeticOperators = [
        '+' => 1,
        '-' => 1,
        '*' => 1,
        '/' => 1,
        '%' => 1
    ];

    protected $logicalOperators = [
        'AND' => 1,
        '&&' => 1,
        'OR' => 1,
        '||' => 1,
        'NOT' => 1,
        '!' => 1
    ];

    protected $rangeOperator = '..';

    protected $keywords = [
        'FOR', 'FILTER', 'SEARCH', 'SORT', 'ASC', 'DESC', 'LIMIT', 'COLLECT', 'INTO', 'AGGREGATE', 'RETURN', 'DISTINCT',
        'WITH', 'GRAPH', 'INBOUND', 'OUTBOUND', 'ANY', 'SHORTEST_PATH', 'K_SHORTEST_PATH', 'PRUNE',
        'LET', 'INSERT', 'UPDATE', 'REPLACE', 'UPSERT', 'REMOVE',
        'ALL', 'NONE', 'AND', 'OR', 'NOT', 'LIKE', 'IN',
        'FALSE',  'TRUE', 'NULL',
    ];

    /*
     * List of recognizable data and the accompanying Expression type it will be mapped too.
     * Strings of an unrecognized nature are always bound.
     */
    protected $argumentTypeExpressionMap = [
        'AssociativeArray' => 'Object',
        'Attribute' => 'Literal',
        'Bind'  => 'Bind',
        'Boolean' => 'Boolean',
        'Collection' => 'Literal',
        'Constant' => 'Constant',
        'Direction' => 'Constant',
        'Document' => 'Object',
        'Function' => 'Function',
        'Graph' => 'String',
        'Id' => 'String',
        'IndexedArray' => 'List',
        'Key' => 'String',
        'List' => 'List',
        'Name' => 'String',
        'Number' => 'Literal',
        'Null' => 'Literal',
        'RegisteredVariable' => 'Literal',
        'Variable' => 'Literal',
        'VariableAttribute' => 'Literal',
        'Object' => 'Object',
        'Range' => 'Literal',
        'String' => 'Bind',
    ];

    /*
     * List of default allowed Data Types
     * The order matters in the compilation of the data
     * String should always go last to trap any remaining unrecognized data in a bind.
     */
    protected $defaultAllowedExpressionTypes = [
        'Number' => 'Number',
        'Boolean' => 'Boolean',
        'Null',
        'Id' => 'Id',
        'Key' => 'Key',
        'VariableAttribute' => 'VariableAttribute',
        'Bind' => 'Bind'
    ];

    public function wrap($value) : string
    {
        return '`'.addcslashes($value, '`').'`';
    }

    public function mapArgumentTypeToExpressionType($argumentType) : string
    {
        return $this->argumentTypeExpressionMap[$argumentType];
    }

    /**
     * @param $value
     * @return bool
     */
    public function isBind($value)
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
    public function isRange($value) : bool
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
    public function isBoolean($value) : bool
    {
        return (is_bool($value) || $value === 'true' ||  $value === 'false');
    }


    /**
     * @param $value
     * @return bool
     */
    public function isNull($value) : bool
    {
        return ($value === null || $value == 'null');
    }

    /**
     * @param $value
     * @return bool
     */
    public function isNumber($value) : bool
    {
        return is_numeric($value);
    }
    
    /**
     * @param $value
     * @return bool
     */
    public function isList($value) : bool
    {
        return is_array($value) && $this->isIndexedArray($value);
    }

    public function isQuery($value) : bool
    {
        return $value instanceof QueryBuilder;
    }

    public function isFunction($value) : bool
    {
        return $value instanceof FunctionExpression;
    }

    public function isLogicalOperator($operator) : bool
    {
        return isset($this->logicalOperators[$operator]);
    }

    public function isComparisonOperator($operator) : bool
    {
        return isset($this->comparisonOperators[$operator]);
    }

    public function isArithmeticOperators($operator) : bool
    {
        return isset($this->arithmeticOperators[$operator]);
    }

    public function isSortDirection($value) : bool
    {
        if (preg_match('/asc|desc/i', $value)) {
            return true;
        }
        return false;
    }

    public function isDirection($value) : bool
    {
        if (preg_match('/outbound|inbound|any/i', $value)) {
            return true;
        }
        return false;
    }

    /**
     * @param $value
     * @return bool
     */
    public function isCollection($value) : bool
    {
        if (is_string($value) && preg_match('/^[a-zA-Z0-9_-]+$/', $value)) {
            return true;
        }
        return false;
    }

    public function isGraph($value) : bool
    {
        return $this->isCollection($value);
    }

    public function isKey($value) : bool
    {
        if (is_string($value) && preg_match("/^[a-zA-Z0-9_-]+\/?[a-zA-Z0-9_\-\:\.\@\(\)\+\,\=\;\$\!\*\'\%]+$/", $value)) {
            return true;
        }
        return false;
    }

    public function isId($value) : bool
    {
        if (is_string($value) && preg_match("/^[a-zA-Z0-9_-]+\/[a-zA-Z0-9_-]+\/?[a-zA-Z0-9_\-\:\.\@\(\)\+\,\=\;\$\!\*\'\%]+$/", $value)) {
            return true;
        }
        return false;
    }

    /**
     * @param $value
     * @return bool
     */
    public function isVariable($value)
    {
        if (is_string($value) && preg_match('/^\$?[a-zA-Z_][a-zA-Z0-9_]*+$/', $value)) {
            return true;
        }
        return false;
    }

    public function isRegisteredVariable($value, $registeredVariables = []) : bool
    {
        return isset($registeredVariables[$value]);
    }


    /**
     * @param $value
     * @return bool
     */
    public function isAttribute($value) : bool
    {
        if (is_string($value) && preg_match('/^(@?[\d\w_]+|`@?[\d\w_]+`)(\[\`.+\`\]|\[[\d\w\*]*\])*(\.(\`.+\`|@?[\d\w]*)(\[\`.+\`\]|\[[\d\w\*]*\])*)*$/', $value)) {
            return true;
        }
        return false;
    }


    /**
     * @param mixed $value
     * @param array $registeredVariables
     * @return bool
     */
    public function isVariableAttribute($value, $registeredVariables = []) : bool
    {
        if (empty($registeredVariables)) {
            return false;
        }
        $variables = implode('|', $registeredVariables);
        if (
            is_string($value)
            && preg_match('/^('.$variables.')(\[\`.+\`\]|\[[\d\w\*]*\])*(\.(\`.+\`|@?[\d\w]*)(\[\`.+\`\]|\[[\d\w\*]*\])*)*$/', $value)
        ) {
            return true;
        }
        return false;
    }


    /**
     * @param $value
     * @return bool
     */
    public function isObject($value) : bool
    {
        if (is_object($value) || (is_array($value) && $this->isAssociativeArray($value))) {
            return true;
        }
        return false;
    }

    public function validateBindParameterSyntax($bindParameter) : bool
    {
        if (preg_match('/^@?[a-zA-Z0-9][a-zA-Z0-9_]*$/', $bindParameter)) {
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
    public function isAssociativeArray(array $array)
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
    public function isIndexedArray(array $array)
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

    public function getAllowedExpressionTypes()
    {
        return $this->defaultAllowedExpressionTypes;
    }
}
