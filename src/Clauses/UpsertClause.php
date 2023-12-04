<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Exceptions\ExpressionTypeException;
use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class UpsertClause extends Clause
{
    /**
     * @var array<mixed>|string|object
     */
    protected array|string|object $search;

    /**
     * @var array<mixed>|string|object
     */
    protected array|string|object $insert;

    /**
     * @var array<mixed>|object|string
     */
    protected array|string|object $update;

    protected string|QueryBuilder|Expression $collection;

    protected bool $replace;

    /**
     * UpsertClause constructor.
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @param  array<mixed>|string|object  $search
     * @param  array<mixed>|string|object  $insert
     * @param  array<mixed>|string|object  $update
     */
    public function __construct(
        array|object|string $search,
        array|object|string $insert,
        array|object|string $update,
        string|QueryBuilder|Expression $collection,
        bool $replace = false
    ) {
        parent::__construct();

        $this->search = $search;
        $this->insert = $insert;
        $this->update = $update;
        $this->collection = $collection;
        $this->replace = $replace;
    }

    /**
     * @throws ExpressionTypeException
     */
    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->search = $queryBuilder->normalizeArgument($this->search, ['RegisteredVariable', 'Key', 'Bind']);
        $this->insert = $queryBuilder->normalizeArgument($this->insert, ['RegisteredVariable', 'Key', 'Bind']);
        $this->update = $queryBuilder->normalizeArgument($this->update, ['Object', 'Bind']);
        $this->collection = $queryBuilder->normalizeArgument($this->collection, ['Collection', 'Bind']);
        $queryBuilder->registerCollections($this->collection->compile($queryBuilder));

        $withClause = 'UPDATE';
        if ($this->replace) {
            $withClause = 'REPLACE';
        }

        return "UPSERT {$this->search->compile($queryBuilder)} " .
            "INSERT {$this->insert->compile($queryBuilder)} $withClause {$this->update->compile($queryBuilder)} " .
            "IN {$this->collection->compile($queryBuilder)}";
    }
}
