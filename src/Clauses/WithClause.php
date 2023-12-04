<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Exceptions\ExpressionTypeException;
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
     * @param  array<string|Expression>  $collections
     */
    public function __construct(
        array $collections
    ) {
        parent::__construct();

        $this->collections = $collections;
    }

    /**
     * @throws ExpressionTypeException
     */
    public function compile(QueryBuilder $queryBuilder): string
    {
        $collections = [];
        foreach ($this->collections as $key => $collection) {
            $collections[$key] = $queryBuilder->normalizeArgument($collection, 'Collection');
            $queryBuilder->registerCollections($collection, 'read');
        }

        $output = 'WITH ';
        $implosion = '';
        foreach ($collections as $collection) {
            $implosion .= ', ' . $collection->compile($queryBuilder);
        }
        $output .= ltrim($implosion, ', ');

        return $output;
    }
}
