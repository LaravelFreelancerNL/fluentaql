<?php

namespace LaravelFreelancerNL\FluentAQL\Traits;

trait ValidatesOperators
{
    /**
     * @param mixed $operator
     * @return bool
     */
    public function isLogicalOperator(mixed $operator): bool
    {
        return isset($this->logicalOperators[strtoupper($operator)]);
    }

    /**
     * @param mixed $operator
     * @return bool
     */
    public function isComparisonOperator(mixed $operator): bool
    {
        return isset($this->comparisonOperators[strtoupper($operator)]);
    }

    /**
     * @param mixed $operator
     * @return bool
     */
    public function isArithmeticOperator(mixed $operator): bool
    {
        return isset($this->arithmeticOperators[$operator]);
    }
}
