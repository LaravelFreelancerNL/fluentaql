<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * AQL literal expression.
 */
class LiteralExpression extends Expression implements ExpressionInterface
{
    /**
     * Compile expression output.
     *
     * @param  QueryBuilder  $queryBuilder
     * @return string
     */
    public function compile(QueryBuilder $queryBuilder = null): string
    {
        return (string) $this->expression;
    }
}
