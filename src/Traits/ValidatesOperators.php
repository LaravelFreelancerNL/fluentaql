<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Traits;

trait ValidatesOperators
{
    public function isLogicalOperator(string $operator): bool
    {
        return isset($this->logicalOperators[strtoupper($operator)]);
    }

    public function isComparisonOperator(string $operator): bool
    {
        return isset($this->comparisonOperators[strtoupper($operator)]);
    }

    public function isArithmeticOperator(string $operator): bool
    {
        return isset($this->arithmeticOperators[$operator]);
    }
}
