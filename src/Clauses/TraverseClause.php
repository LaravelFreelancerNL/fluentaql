<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class TraverseClause extends Clause
{
    protected string|QueryBuilder|Expression $direction;

    protected string|QueryBuilder|Expression $startVertex;

    protected null|string|QueryBuilder|Expression $toVertex;

    /**
     * TraverseClause constructor.
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function __construct(
        string|QueryBuilder|Expression $startVertex,
        string|QueryBuilder|Expression $direction = 'outbound',
        string|QueryBuilder|Expression $toVertex = null
    ) {
        $this->direction = $direction;
        $this->startVertex = $startVertex;
        $this->toVertex = $toVertex;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->startVertex = $queryBuilder->normalizeArgument($this->startVertex, 'Id');
        $this->direction = $queryBuilder->normalizeArgument($this->direction, 'GraphDirection');

        if ($this->toVertex !== null) {
            $this->toVertex = $queryBuilder->normalizeArgument($this->toVertex, 'Id');
        }


        $output = $this->direction->compile($queryBuilder);

        $output .= $this->traverseType();

        $output .= ' ' . $this->startVertex->compile($queryBuilder);
        if (isset($this->toVertex)) {
            $output .= ' TO ' . $this->toVertex->compile($queryBuilder);
        }

        return $output;
    }

    /**
     * Default path type
     *
     * @return string
     */
    protected function traverseType(): string
    {
        return '';
    }
}
