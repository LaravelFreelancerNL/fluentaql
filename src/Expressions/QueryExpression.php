<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * AQL literal expression
 */
class QueryExpression extends Expression implements ExpressionInterface
{
    /** @var QueryBuilder */
    protected $expression;

    function __construct(QueryBuilder $expression)
    {
        parent::__construct($expression);

        //Query Expressions are by definition subqueries.
        $this->expression->setSubQuery();
    }

    function compile(QueryBuilder $qb)
    {
        return $this->expression->compile();
    }

    function __toString()
    {
        return $this->expression->toAql();
    }
}
