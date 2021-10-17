<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class StringExpression extends Expression implements ExpressionInterface
{
    public function compile(QueryBuilder $queryBuilder = null): string
    {
        return (string) json_encode($this->expression, JSON_UNESCAPED_SLASHES);
    }
}
