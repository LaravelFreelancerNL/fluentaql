<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class UpsertClause extends Clause
{
    protected $search;

    protected $insert;

    protected $with;

    protected $collection;

    protected $replace;

    /**
     * UpsertClause constructor.
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @param $search
     * @param $insert
     * @param $with
     * @param $collection
     * @param bool $replace
     */
    public function __construct($search, $insert, $with, $collection, $replace = false)
    {
        parent::__construct();

        $this->search = $search;
        $this->insert = $insert;
        $this->with = $with;
        $this->collection = $collection;
        $this->replace = $replace;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->search = $queryBuilder->normalizeArgument($this->search, ['RegisteredVariable', 'Key', 'Bind']);
        $this->insert = $queryBuilder->normalizeArgument($this->insert, ['RegisteredVariable', 'Key', 'Bind']);
        $this->with = $queryBuilder->normalizeArgument($this->with, ['Object', 'Bind']);
        $this->collection = $queryBuilder->normalizeArgument($this->collection, ['Collection', 'Bind']);
        $queryBuilder->registerCollections($this->collection->compile($queryBuilder));

        $withClause = 'UPDATE';
        if ($this->replace) {
            $withClause = 'REPLACE';
        }

        return "UPSERT {$this->search->compile($queryBuilder)} " .
            "INSERT {$this->insert->compile($queryBuilder)} {$withClause} {$this->with->compile($queryBuilder)} " .
            "IN {$this->collection->compile($queryBuilder)}";
    }
}
