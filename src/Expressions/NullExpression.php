<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * AQL literal expression.
 */
class NullExpression extends LiteralExpression implements ExpressionInterface
{
    /**
     * Create an expression.
     *
     * @param mixed $expression
     */
    public function __construct()
    {
    }

    /**
     * Compile expression output.
     *
     * @param  QueryBuilder|null  $queryBuilder
     * @return string
     */
    public function compile(QueryBuilder $queryBuilder = null): string
    {
        return 'null';
    }
}
