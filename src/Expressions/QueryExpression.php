<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Query expression.
 */
class QueryExpression extends Expression implements ExpressionInterface
{
    /** @var QueryBuilder */
    protected $expression;

    public function __construct(QueryBuilder $expression)
    {
        parent::__construct($expression);

        //Query Expressions are by definition subqueries.
        $this->expression->setSubQuery();
    }

    public function compile()
    {
        $this->expression = $this->expression->compile();

        return $this->expression;
    }

    public function __toString()
    {
        return $this->compile()->query;
    }
}
