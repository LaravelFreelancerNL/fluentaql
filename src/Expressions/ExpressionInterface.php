<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

interface ExpressionInterface
{
    public function compile(QueryBuilder $queryBuilder): string;
}
