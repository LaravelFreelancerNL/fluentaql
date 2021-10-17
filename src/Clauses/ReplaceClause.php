<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class ReplaceClause extends Clause
{
    /**
     * @var array<mixed>|string|QueryBuilder|Expression
     */
    protected array|string|QueryBuilder|Expression $document;

    /**
     * @var array<mixed>|string|QueryBuilder|Expression
     */
    protected array|string|QueryBuilder|Expression $with;

    protected string|QueryBuilder|Expression $collection;

    /**
     * @param array<mixed>|string|QueryBuilder|Expression $document
     * @param array<mixed>|string|QueryBuilder|Expression $with
     */
    public function __construct(
        array|string|QueryBuilder|Expression $document,
        array|string|QueryBuilder|Expression $with,
        string|QueryBuilder|Expression $collection
    ) {
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


        return "REPLACE {$this->document->compile($queryBuilder)} " .
            "WITH {$this->with->compile($queryBuilder)} " .
            "IN {$this->collection->compile($queryBuilder)}";
    }
}
