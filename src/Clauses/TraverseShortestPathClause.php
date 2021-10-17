<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Clauses;

class TraverseShortestPathClause extends TraverseClause
{
    protected function traverseType(): string
    {
        return ' SHORTEST_PATH';
    }
}
