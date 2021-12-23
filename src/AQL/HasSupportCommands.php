<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Clauses\RawClause;
use LaravelFreelancerNL\FluentAQL\Exceptions\BindException;
use LaravelFreelancerNL\FluentAQL\Expressions\BindExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\Expressions\LiteralExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Trait hasFunctions.
 *
 * AQL Function API calls.
 */
trait HasSupportCommands
{
    abstract public function addCommand($command);

    /**
     * @param object|array<mixed>|string|int|float|bool|null $data
     */
    abstract public function bind(
        object|array|string|int|float|bool|null $data,
        string $to = null
    ): BindExpression;

    /**
     * @param array<object|array<mixed>|string|int|float|bool|null> $binds
     * @param array<string, string|Expression|QueryBuilder> $collections
     * @throws BindException
     */
    public function raw(
        string $aql,
        array $binds = [],
        array $collections = []
    ): self {
        $this->bindArrayValues($binds);

        foreach ($collections as $mode => $modeCollections) {
            $this->registerCollections($modeCollections, $mode);
        }

        $this->addCommand(new RawClause($aql));

        return $this;
    }

    abstract public function registerCollections($collections, $mode = 'write');

    /**
     * @param array<array-key, array<array-key, mixed>|null|object|scalar> $binds
     * @param array<string, string|Expression|QueryBuilder> $collections
     * @throws BindException
     */
    public function rawExpression(
        string $aql,
        array $binds = [],
        array $collections = []
    ): LiteralExpression {
        $this->bindArrayValues($binds);

        foreach ($collections as $mode => $modeCollections) {
            $this->registerCollections($modeCollections, $mode);
        }

        return new LiteralExpression($aql);
    }
}
