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

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->expression->registerVariable($queryBuilder->getVariables());

        $this->expression = $this->expression->compile($queryBuilder);

        $queryBuilder->binds = array_unique(array_merge($queryBuilder->binds, $this->expression->binds));

        // Extract collections
        if (isset($this->expression->collections)) {
            foreach ($this->expression->collections as $mode => $collections) {
                $queryBuilder->registerCollections($this->expression->collections[$mode], $mode);
            }
        }

        return '(' . $this->expression . ')';
    }
}
