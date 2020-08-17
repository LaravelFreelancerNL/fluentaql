<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * String expression.
 */
class StringExpression extends Expression implements ExpressionInterface
{
    /**
     * Compile expression output.
     *
     * @param  QueryBuilder|null  $queryBuilder
     * @return string
     */
    public function compile(QueryBuilder $queryBuilder = null): string
    {
        return json_encode($this->expression, JSON_UNESCAPED_SLASHES);
    }
}
