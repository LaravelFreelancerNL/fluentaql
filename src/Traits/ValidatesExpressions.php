<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

trait ValidatesExpressions
{
    use ValidatesOperators;
    use ValidatesPredicates;
    use ValidatesReferences;

    public function isRange(mixed $value): bool
    {
        if (is_string($value) && preg_match('/^[0-9]+(?:\.[0-9]+)?+\.{2}[0-9]+(?:\.[0-9]+)?$/', $value)) {
            return true;
        }

        return false;
    }

    public function isBoolean(mixed $value): bool
    {
        return is_bool($value) || $value === 'true' || $value === 'false';
    }

    public function isNull(mixed $value): bool
    {
        return $value === null || $value == 'null';
    }

    public function isNumber(mixed $value): bool
    {
        return is_numeric($value) && !is_string($value);
    }

    public function isList(mixed $value): bool
    {
        return is_array($value) && $this->isIndexedArray($value);
    }

    public function isQuery(mixed $value): bool
    {
        return $value instanceof QueryBuilder;
    }

    /**
     * @param  mixed  $value
     */
    public function isFunction($value): bool
    {
        return $value instanceof FunctionExpression;
    }

    /**
     * @param  mixed  $value
     */
    public function isSortDirection($value): bool
    {
        if (is_string($value) && preg_match('/asc|desc/i', $value)) {
            return true;
        }

        return false;
    }

    /**
     * @param  mixed  $value
     */
    public function isGraphDirection($value): bool
    {
        if (is_string($value) && preg_match('/outbound|inbound|any/i', $value)) {
            return true;
        }

        return false;
    }

    /**
     * @param  mixed  $value
     */
    public function isCollection($value): bool
    {
        if (is_string($value) && preg_match('/^[a-zA-Z0-9_-]+$/', $value)) {
            return true;
        }

        return false;
    }

    /**
     * @param  mixed  $value
     */
    public function isGraph($value): bool
    {
        return $this->isCollection($value);
    }

    /**
     * @param  mixed  $value
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
     * @param  mixed  $value
     */
    public function isId($value): bool
    {
        if (
            is_string($value) &&
            preg_match("/^[a-zA-Z0-9_-]+\/(\/?[a-zA-Z0-9_\-\:\.\@\(\)\+\,\=\;\$\!\*\'\%])+$/", $value)
        ) {
            return true;
        }

        return false;
    }

    public function isObject(mixed $value): bool
    {
        if (is_object($value) || (is_array($value) && $this->isAssociativeArray($value))) {
            return true;
        }

        return false;
    }

    public function isBindParameter(string $bindParameter): bool
    {
        if (preg_match('/^@?[a-zA-Z0-9][a-zA-Z0-9_]*$/', $bindParameter)) {
            return true;
        }

        return false;
    }

    /**
     * Check if the array is associative.
     *
     * @param  array<mixed>  $array
     */
    public function isAssociativeArray(array $array): bool
    {
        if (empty($array)) {
            return false;
        }

        return !ctype_digit(implode('', array_keys($array)));
    }

    /**
     * Check if the array is numeric.
     *
     * @param  array<mixed>  $array
     */
    public function isIndexedArray(array $array): bool
    {
        if (empty($array)) {
            return true;
        }

        return ctype_digit(implode('', array_keys($array)));
    }
}
