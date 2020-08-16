<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

class InsertClause extends Clause
{
    protected $document;

    protected $collection;

    public function __construct($document, $collection)
    {
        parent::__construct();

        $this->document = $document;
        $this->collection = $collection;
    }

    public function compile(): string
    {
        return "INSERT {$this->document} IN {$this->collection}";
    }
}
