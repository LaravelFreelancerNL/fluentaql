<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL;

abstract class QueryElement
{
    /**
     * Compile clause|expression output.
     *
     * @param  QueryBuilder  $queryBuilder
     * @return string
     */
    abstract public function compile(QueryBuilder $queryBuilder): string;
}
