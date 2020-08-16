<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class ConditionalExpression extends Expression implements ExpressionInterface
{
    /** @var string */
    protected $parameters = [];

    /**
     * Create conditional expression.
     *
     * @param Expression $if
     * @param Expression $then
     * @param Expression $else
     */
    public function __construct($if, $then, $else = null)
    {
        $this->parameters['if'] = $if;
        $this->parameters['then'] = $then;
        $this->parameters['else'] = $else;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        return $this->parameters['if']->compile($queryBuilder) . ' ? ' . $this->parameters['then']->compile($queryBuilder) . ' : ' . $this->parameters['else']->compile($queryBuilder);
    }
}
