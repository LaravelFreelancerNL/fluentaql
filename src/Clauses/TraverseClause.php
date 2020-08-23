<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class TraverseClause extends Clause
{
    protected $direction;

    protected $startVertex;

    protected $toVertex;

    /**
     * TraverseClause constructor.
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @param string  $startVertex
     * @param string  $direction
     * @param string|null  $toVertex
     */
    public function __construct(
        string $startVertex,
        $direction = 'outbound',
        $toVertex = null
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
