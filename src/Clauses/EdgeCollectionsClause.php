<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\LiteralExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\StringExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class EdgeCollectionsClause extends Clause
{
    protected $edgeCollections;

    public function __construct(array $edgeCollections)
    {
        parent::__construct();

        $this->edgeCollections = $edgeCollections;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->edgeCollections = array_map(function ($edgeCollection) use ($queryBuilder) {
            if (is_string($edgeCollection)) {
                return $queryBuilder->normalizeArgument($edgeCollection, 'Collection');
            }

            $edgeCollection[0] = $queryBuilder->normalizeArgument($edgeCollection[0], 'Collection');
            if (isset($edgeCollections[1]) && !$this->grammar->isDirection($edgeCollections[1])) {
                unset($edgeCollections[1]);
            }
            return $edgeCollection;
        }, $this->edgeCollections);

        $output = array_map(function ($edgeCollection) use ($queryBuilder) {
            if ($edgeCollection instanceof LiteralExpression) {
                return $edgeCollection->compile($queryBuilder) . ' ';
            }

            $edgeCollectionOutput = '';

            $edgeCollectionOutput .= $edgeCollection[0]->compile($queryBuilder);
            return $edgeCollectionOutput;
        }, $this->edgeCollections);

        return implode(', ', $output);
    }
}
