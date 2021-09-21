<?php

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

trait ValidatesExpressions
{
    use ValidatesOperators;
    use ValidatesReferences;

    /**
     * @param mixed $value
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
     * @param mixed $value
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
     * @param mixed $value
     *
     * @return bool
     */
    public function isList($value): bool
    {
        return is_array($value) && $this->isIndexedArray($value);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isQuery($value): bool
    {
        return $value instanceof QueryBuilder;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isFunction($value): bool
    {
        return $value instanceof FunctionExpression;
    }

    /**
     * @param mixed $value
     * @return bool
     */
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
     * @param mixed $value
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

    /**
     * @param mixed $value
     * @return bool
     */
    public function isGraph($value): bool
    {
        return $this->isCollection($value);
    }

    /**
     * @param mixed $value
     * @return bool
     */
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

    /**
     * @param mixed $value
     * @return bool
     */
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
     * @param mixed $value
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

    /**
     * @param mixed $bindParameter
     * @return bool
     */
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
    public function isAssociativeArray(array $array): bool
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
    public function isIndexedArray(array $array): bool
    {
        if (empty($array)) {
            return true;
        }

        return ctype_digit(implode('', array_keys($array)));
    }
}
