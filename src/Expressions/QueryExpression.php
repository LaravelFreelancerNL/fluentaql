<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Query expression.
 */
class QueryExpression extends Expression implements ExpressionInterface
{
    protected QueryBuilder $queryBuilder;

    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->queryBuilder->registerVariable($queryBuilder->getVariables());

        $this->queryBuilder = $this->queryBuilder->compile();

        $queryBuilder->binds = array_unique(array_merge($queryBuilder->binds, $this->queryBuilder->binds));

        // Extract collections
        if (isset($this->queryBuilder->collections)) {
            foreach (array_keys($this->queryBuilder->collections) as $mode) {
                $queryBuilder->registerCollections($this->queryBuilder->collections[$mode], $mode);
            }
        }

        return '(' . $this->queryBuilder . ')';
    }
}
