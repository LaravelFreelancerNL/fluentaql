<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\Expressions\ExpressionInterface;
use LaravelFreelancerNL\FluentAQL\Expressions\LiteralExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class EdgeCollectionsClause extends Clause
{
    protected $edgeCollections;

    public function __construct(array $edgeCollections)
    {
        parent::__construct();

        $this->edgeCollections = $edgeCollections;
    }

    /**
     *
     * @SuppressWarnings(PHPMD.UndefinedVariable)
     *
     * @param  QueryBuilder  $queryBuilder
     * @return string
     */
    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->edgeCollections = array_map(function ($edgeCollection) use ($queryBuilder) {
            if (!$queryBuilder->grammar->isGraphDirection($edgeCollection)) {
                return $queryBuilder->normalizeArgument($edgeCollection, ['Collection', 'Query', 'Bind']);
            }
            return $edgeCollection;
        }, $this->edgeCollections);

        $output = '';
        foreach ($this->edgeCollections as $value) {
            if ($value instanceof ExpressionInterface) {
                $output .= $value->compile($queryBuilder) . ', ';
            }
            if (is_string($value)) {
                $output .= $value . ' ';
            }
        }

        return rtrim($output, ', ');
    }
}
