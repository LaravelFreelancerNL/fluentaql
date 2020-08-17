<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * AQL literal expression.
 */
class ConstantExpression extends LiteralExpression implements ExpressionInterface
{
    /**
     * Compile expression output.
     *
     * @param  QueryBuilder|null  $queryBuilder
     * @return string
     */
    public function compile(QueryBuilder $queryBuilder = null): string
    {
        return strtoupper($this->expression);
    }
}
