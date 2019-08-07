<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

Interface ExpressionInterface
{
    function compile(QueryBuilder $qb);
}