<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class UpdateClause extends Clause
{
    /**
     * @var array<mixed>|object|string
     */
    protected array|object|string $document;

    /**
     * @var array<mixed>|object|string
     */
    protected array|object|string $with;

    /**
     * @var string|QueryBuilder|Expression
     */
    protected string|QueryBuilder|Expression $collection;

    /**
     * @param  array<mixed>|object|string  $document
     * @param  array<mixed>|object|string  $with
     * @param  string|QueryBuilder|Expression  $collection
     */
    public function __construct(
        array|object|string $document,
        array|object|string $with,
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

        return "UPDATE {$this->document->compile($queryBuilder)} ".
            "WITH {$this->with->compile($queryBuilder)} ".
            "IN {$this->collection->compile($queryBuilder)}";
    }
}
