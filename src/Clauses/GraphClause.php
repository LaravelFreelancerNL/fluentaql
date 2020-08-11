<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

class GraphClause extends Clause
{
    protected $graphName;

    public function __construct($graphName)
    {
        $this->graphName = $graphName;
    }

    public function compile()
    {
        return 'GRAPH ' . $this->graphName;
    }
}
