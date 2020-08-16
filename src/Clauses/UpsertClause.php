<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

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

    public function compile(): string
    {
        $withClause = 'UPDATE';
        if ($this->replace) {
            $withClause = 'REPLACE';
        }

        return "UPSERT {$this->search} INSERT {$this->insert} {$withClause} {$this->with} IN {$this->collection}";
    }
}
