<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class UpsertClause extends Clause
{
    /**
     * @var array<mixed>|string|QueryBuilder|Expression $search
     */
    protected array|string|QueryBuilder|Expression $search;

    /**
     * @var array<mixed>|string|QueryBuilder|Expression $insert
     */
    protected array|string|QueryBuilder|Expression $insert;

    /**
     * @var array<mixed>|object $update
     */
    protected array|string|object $update;

    protected string|QueryBuilder|Expression $collection;

    protected bool $replace;

    /**
     * UpsertClause constructor.
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function __construct(
        array|string|QueryBuilder|Expression $search,
        array|string|QueryBuilder|Expression $insert,
        array|string|object $update,
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
            "INSERT {$this->insert->compile($queryBuilder)} {$withClause} {$this->update->compile($queryBuilder)} " .
            "IN {$this->collection->compile($queryBuilder)}";
    }
}
