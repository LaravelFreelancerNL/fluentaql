<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Exceptions\ExpressionTypeException;
use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\Expressions\ExpressionInterface;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class EdgeCollectionsClause extends Clause
{
    /**
     * @var array<array<string>|Expression> $edgeCollections
     */
    protected array $edgeCollections;

    /**
     * @param array<array<string>|Expression> $edgeCollections
     */
    public function __construct(array $edgeCollections)
    {
        parent::__construct();

        $this->edgeCollections = $edgeCollections;
    }

    /**
     * @SuppressWarnings(PHPMD.UndefinedVariable)
     *
     * @throws ExpressionTypeException
     */
    public function compile(QueryBuilder $queryBuilder): string
    {
        /** @var array<string|Expression> $edgeCollections */
        $edgeCollections = array_map(function ($edgeCollection) use ($queryBuilder) {
            if (!$queryBuilder->grammar->isGraphDirection($edgeCollection)) {
                return $queryBuilder->normalizeArgument($edgeCollection, ['Collection', 'Query', 'Bind']);
            }

            return $edgeCollection;
        }, $this->edgeCollections);

        $output = '';
        foreach ($edgeCollections as $value) {
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
