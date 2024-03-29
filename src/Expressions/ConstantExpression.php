<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * AQL literal expression.
 */
class ConstantExpression extends LiteralExpression implements ExpressionInterface
{
    /**
     * Compile expression output.
     */
    public function compile(QueryBuilder $queryBuilder = null): string
    {
        return strtoupper((string) $this->expression);
    }
}
