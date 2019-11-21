<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

class UpdateClause extends Clause
{
    protected $document;

    protected $with;

    protected $collection;

    public function __construct($document, $with, $collection)
    {
        parent::__construct();

        $this->document = $document;
        $this->with = $with;
        $this->collection = $collection;
    }

    public function compile()
    {
        return "UPDATE {$this->document} WITH {$this->with} IN {$this->collection}";
    }
}
