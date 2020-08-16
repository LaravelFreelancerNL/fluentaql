<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

interface ExpressionInterface
{
    public function compile(QueryBuilder $queryBuilder): string;
}
