<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * AQL literal expression.
 */
class BooleanExpression extends LiteralExpression implements ExpressionInterface
{
    /**
     * Compile expression output.
     */
    public function compile(QueryBuilder $queryBuilder = null): string
    {
        return ($this->expression) ? 'true' : 'false';
    }
}
