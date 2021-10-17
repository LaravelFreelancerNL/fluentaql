<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Clauses;

class TraverseKShortestPathsClause extends TraverseClause
{
    protected function traverseType(): string
    {
        return ' K_SHORTEST_PATHS';
    }
}
