<?php

namespace LaravelFreelancerNL\FluentAQL;

abstract class QueryElement
{
    /**
     * Compile expression output.
     *
     * @param  QueryBuilder  $queryBuilder
     * @return string
     */
    public function compile(QueryBuilder $queryBuilder)
    {
    }

    /**
     * Get expression.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->compile();
    }
}
