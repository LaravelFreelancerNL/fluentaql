<?php

declare(strict_types=1);

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
    public function compile(QueryBuilder $queryBuilder): string
    {
        return (string) $this->expression;
    }
}
