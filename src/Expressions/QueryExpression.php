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
    }

    public function compile(QueryBuilder $parentQueryBuilder): string
    {
        $this->expression->registerVariable($parentQueryBuilder->getVariables());

        $this->expression = $this->expression->compile();

        $parentQueryBuilder->binds = array_unique(array_merge($parentQueryBuilder->binds, $this->expression->binds));

        // Extract collections
        if (isset($this->expression->collections)) {
            foreach ($this->expression->collections as $collection => $mode) {
                $parentQueryBuilder->registerCollections($this->expression->collections[$collection], $mode);
            }
        }

        return '(' . $this->expression . ')';
    }

    public function __toString()
    {
        return $this->compile()->query;
    }
}
