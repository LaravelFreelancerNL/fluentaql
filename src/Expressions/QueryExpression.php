<?php
namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Query expression
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
        return $this->expression->toAql();
    }

    public function __toString()
    {
        return $this->compile();
    }
}
