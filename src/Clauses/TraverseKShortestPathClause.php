<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

class TraverseKShortestPathClause extends TraverseClause
{
    protected function traverseType(): string
    {
        return ' K_SHORTEST_PATHS';
    }
}
