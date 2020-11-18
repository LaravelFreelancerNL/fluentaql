<?php

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

trait ValidatesExpressions
{
    /**
     * @param $value
     *
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
     *
     * @return bool
     */
    public function isCollectionBind($value)
    {
        if (is_string($value)) {
            return true;
        }

        return false;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function isRange($value): bool
    {
        if (is_string($value) && preg_match('/^[0-9]+(?:\.[0-9]+)?+\.{2}[0-9]+(?:\.[0-9]+)?$/', $value)) {
            return true;
        }

        return false;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function isBoolean($value): bool
    {
        return is_bool($value) || $value === 'true' || $value === 'false';
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function isNull($value): bool
    {
        return $value === null || $value == 'null';
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function isNumber($value): bool
    {
        return is_numeric($value) && !is_string($value);
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function isList($value): bool
    {
        return is_array($value) && $this->isIndexedArray($value);
    }

    public function isQuery($value): bool
    {
        return $value instanceof QueryBuilder;
    }

    public function isFunction($value): bool
    {
        return $value instanceof FunctionExpression;
    }

    public function isLogicalOperator($operator): bool
    {
        return isset($this->logicalOperators[strtoupper($operator)]);
    }

    public function isComparisonOperator($operator): bool
    {
        return isset($this->comparisonOperators[strtoupper($operator)]);
    }

    public function isArithmeticOperator($operator): bool
    {
        return isset($this->arithmeticOperators[$operator]);
    }

    public function isSortDirection($value): bool
    {
        if (is_string($value) && preg_match('/asc|desc/i', $value)) {
            return true;
        }

        return false;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isGraphDirection($value): bool
    {
        if (is_string($value) && preg_match('/outbound|inbound|any/i', $value)) {
            return true;
        }

        return false;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function isCollection($value): bool
    {
        if (is_string($value) && preg_match('/^[a-zA-Z0-9_-]+$/', $value)) {
            return true;
        }

        return false;
    }

    public function isGraph($value): bool
    {
        return $this->isCollection($value);
    }

    public function isKey($value): bool
    {
        if (
            is_string($value) &&
            preg_match("/^[a-zA-Z0-9_-]+\/?[a-zA-Z0-9_\-\:\.\@\(\)\+\,\=\;\$\!\*\'\%]+$/", $value)
        ) {
            return true;
        }

        return false;
    }

    public function isId($value): bool
    {
        if (
            is_string($value) &&
            preg_match("/^[a-zA-Z0-9_-]+\/[a-zA-Z0-9_-]+\/?[a-zA-Z0-9_\-\:\.\@\(\)\+\,\=\;\$\!\*\'\%]+$/", $value)
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function isVariable($value)
    {
        if (is_string($value) && preg_match('/^\$?[a-zA-Z_][a-zA-Z0-9_]*+$/', $value)) {
            return true;
        }

        return false;
    }

    public function isRegisteredVariable($value, $registeredVariables = []): bool
    {
        return isset($registeredVariables[$value]);
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function isAttribute($value): bool
    {
        $pattern = '/^(@?[\d\w_]+|`@?[\d\w_]+`)(\[\`.+\`\]|\[[\d\w\*]*\])*'
            . '(\.(\`.+\`|@?[\d\w]*)(\[\`.+\`\]|\[[\d\w\*]*\])*)*$/';
        if (
            is_string($value) &&
            preg_match($pattern, $value)
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param mixed $value
     * @param array $registeredVariables
     * @return bool
     */
    public function isReference($value, $registeredVariables = []): bool
    {
        $variables = '';
        if (!empty($registeredVariables)) {
            $variables = implode('|', $registeredVariables);
        }

        if (! is_string($value)) {
            return false;
        }

        return (bool) preg_match(
            '/^('
                . $variables
                . '|CURRENT|NEW|OLD)(\[\`.+\`\]|\[[\d\w\*]*\])*(\.(\`.+\`|@?[\d\w]*)(\[\`.+\`\]|\[[\d\w\*]*\])*)*$/',
            $value
        );
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function isObject($value): bool
    {
        if (is_object($value) || (is_array($value) && $this->isAssociativeArray($value))) {
            return true;
        }

        return false;
    }

    public function isBindParameter($bindParameter): bool
    {
        if (preg_match('/^@?[a-zA-Z0-9][a-zA-Z0-9_]*$/', $bindParameter)) {
            return true;
        }

        return false;
    }

    /**
     * Check if the array is associative.
     *
     * @param array $array
     *
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
     * Check if the array is numeric.
     *
     * @param array $array
     *
     * @return bool
     */
    public function isIndexedArray(array $array)
    {
        if (empty($array)) {
            return true;
        }

        return ctype_digit(implode('', array_keys($array)));
    }
}
