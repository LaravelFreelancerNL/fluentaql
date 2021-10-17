<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class InsertClause extends Clause
{
    /**
     * @var object|string|mixed[] $document
     */
    protected array|object|string $document;

    protected string|QueryBuilder|Expression $collection;

    /**
     * @param array<mixed>|object|string $document
     */
    public function __construct(
        array|object|string $document,
        string|QueryBuilder|Expression $collection
    ) {
        parent::__construct();

        $this->document = $document;

        $this->collection = $collection;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->document = $queryBuilder->normalizeArgument($this->document, ['RegisteredVariable', 'Object', 'Bind']);

        $queryBuilder->registerCollections($this->collection);
        $this->collection = $queryBuilder->normalizeArgument($this->collection, ['Collection', 'Bind']);


        return "INSERT {$this->document->compile($queryBuilder)} IN {$this->collection->compile($queryBuilder)}";
    }
}
