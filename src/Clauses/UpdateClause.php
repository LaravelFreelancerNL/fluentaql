<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

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

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->document = $queryBuilder->normalizeArgument(
            $this->document,
            ['RegisteredVariable', 'Key', 'Object', 'Bind']
        );
        $this->with = $queryBuilder->normalizeArgument($this->with, ['Object', 'Bind']);
        $this->collection = $queryBuilder->normalizeArgument($this->collection, ['Collection', 'Bind']);
        $queryBuilder->registerCollections($this->collection->compile($queryBuilder));

        return "UPDATE {$this->document->compile($queryBuilder)} " .
            "WITH {$this->with->compile($queryBuilder)} " .
            "IN {$this->collection->compile($queryBuilder)}";
    }
}
