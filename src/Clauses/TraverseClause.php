<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\StringExpression;

class TraverseClause extends Clause
{
    protected $direction;
    protected $startVertex;
    protected $toVertex;
    protected $kShortestPaths = false;

    public function __construct(StringExpression $startVertex, $direction = 'outbound', $toVertex = null, bool $kShortestPaths = false)
    {
        $this->direction = $direction;
        $this->startVertex = $startVertex;
        $this->toVertex = $toVertex;
        $this->kShortestPaths = $kShortestPaths;
    }

    public function compile()
    {
        $output = $this->direction;
        if (isset($this->toVertex)) {
            $output .= ($this->kShortestPaths) ? ' K_SHORTEST_PATHS' : ' SHORTEST_PATH';
        }
        $output .= ' '.$this->startVertex;
        if (isset($this->toVertex)) {
            $output .= ' TO '.$this->toVertex;
        }

        return $output;
    }
}
