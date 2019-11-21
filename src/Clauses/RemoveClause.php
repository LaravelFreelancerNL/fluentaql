<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

class RemoveClause extends Clause
{
    protected $document;

    protected $collection;

    public function __construct($document, $collection)
    {
        parent::__construct();

        $this->document = $document;
        $this->collection = $collection;
    }

    public function compile()
    {
        return "REMOVE {$this->document} IN {$this->collection}";
    }
}
