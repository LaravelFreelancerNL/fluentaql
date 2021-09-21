<?php

namespace LaravelFreelancerNL\FluentAQL\Traits;

trait ValidatesOperators
{
    /**
     * @param mixed $operator
     * @return bool
     */
    public function isLogicalOperator($operator): bool
    {
        return isset($this->logicalOperators[strtoupper($operator)]);
    }

    /**
     * @param mixed $operator
     * @return bool
     */
    public function isComparisonOperator($operator): bool
    {
        return isset($this->comparisonOperators[strtoupper($operator)]);
    }

    /**
     * @param mixed $operator
     * @return bool
     */
    public function isArithmeticOperator($operator): bool
    {
        return isset($this->arithmeticOperators[$operator]);
    }
}
