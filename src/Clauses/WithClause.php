<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class WithClause extends Clause
{
    /**
     * @var array<string|Expression>
     */
    protected array $collections;

    /**
     * WithClause constructor.
     *
     * @param array<string|Expression> $collections
     */
    public function __construct(
        array $collections
    ) {
        parent::__construct();

        $this->collections = $collections;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        foreach ($this->collections as $key => $collection) {
            $this->collections[$key] = $queryBuilder->normalizeArgument($collection, 'Collection');
            $queryBuilder->registerCollections($collection, 'read');
        }

        $output = "WITH ";
        $implosion = '';
        foreach ($this->collections as $key => $collection) {
            $implosion .= ', ' . $collection->compile($queryBuilder);
        }
        $output .= ltrim($implosion, ', ');

        return $output;
    }
}
