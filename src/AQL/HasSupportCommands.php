<?php

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Clauses\RawClause;
use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\LiteralExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\RawExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Trait hasFunctions.
 *
 * AQL Function API calls.
 */
trait HasSupportCommands
{
    /**
     * @param  string  $aql
     * @param  array  $binds
     * @param  array  $collections
     * @return $this|QueryBuilder
     */
    public function raw(string $aql, $binds = [], $collections = []): QueryBuilder
    {
        foreach ($binds as $key => $value) {
            $this->bind($value, $key);
        }

        foreach ($collections as $mode => $modeCollections) {
            $this->registerCollections($modeCollections, $mode);
        }

        $this->addCommand(new RawClause($aql));

        return $this;
    }

    /**
     * @param  string  $aql
     * @param  array  $binds
     * @param  array  $collections
     * @return LiteralExpression
     */
    public function rawExpression(string $aql, $binds = [], $collections = []): LiteralExpression
    {
        foreach ($binds as $key => $value) {
            $this->bind($value, $key);
        }

        foreach ($collections as $mode => $modeCollections) {
            $this->registerCollections($modeCollections, $mode);
        }

        return new LiteralExpression($aql);
    }
}
