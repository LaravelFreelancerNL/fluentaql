<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL;

abstract class QueryElement
{
    /**
     * Compile clause|expression output.
     */
    abstract public function compile(QueryBuilder $queryBuilder): string;
}
