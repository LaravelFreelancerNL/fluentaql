<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

class TraverseShortestPathClause extends TraverseClause
{
    protected function traverseType(): string
    {
        return ' SHORTEST_PATH';
    }
}
