<?php

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
