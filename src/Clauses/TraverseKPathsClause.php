<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

class TraverseKPathsClause extends TraverseClause
{
    protected function traverseType(): string
    {
        return ' K_PATHS';
    }
}
