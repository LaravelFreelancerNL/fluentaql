<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Traits;

trait ValidatesOperators
{
    /**
     * @param string $operator
     * @return bool
     */
    public function isLogicalOperator(string $operator): bool
    {
        return isset($this->logicalOperators[strtoupper($operator)]);
    }

    /**
     * @param string $operator
     * @return bool
     */
    public function isComparisonOperator(string $operator): bool
    {
        return isset($this->comparisonOperators[strtoupper($operator)]);
    }

    /**
     * @param string $operator
     * @return bool
     */
    public function isArithmeticOperator(string $operator): bool
    {
        return isset($this->arithmeticOperators[$operator]);
    }
}
