<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Clauses\RawClause;
use LaravelFreelancerNL\FluentAQL\Exceptions\BindException;
use LaravelFreelancerNL\FluentAQL\Expressions\LiteralExpression;

/**
 * Trait hasFunctions.
 *
 * AQL Function API calls.
 */
trait HasSupportCommands
{

    abstract public function addCommand($command);

    abstract public function bind(mixed $data, string $to = null);

    /**
     * @param array<mixed> $binds
     * @param array<mixed> $collections
     * @throws BindException
     */
    public function raw(
        string $aql,
        array $binds = [],
        array $collections = []
    ): self {
        foreach ($binds as $key => $value) {
            $this->bind($value, $key);
        }

        foreach ($collections as $mode => $modeCollections) {
            $this->registerCollections($modeCollections, $mode);
        }

        $this->addCommand(new RawClause($aql));

        return $this;
    }

    abstract public function registerCollections($collections, $mode = 'write');

    /**
     * @param array<mixed> $binds
     * @param array<mixed> $collections
     * @throws BindException
     */
    public function rawExpression(
        string $aql,
        array $binds = [],
        array $collections = []
    ): LiteralExpression {
        foreach ($binds as $key => $value) {
            $this->bind($value, $key);
        }

        foreach ($collections as $mode => $modeCollections) {
            $this->registerCollections($modeCollections, $mode);
        }

        return new LiteralExpression($aql);
    }
}
