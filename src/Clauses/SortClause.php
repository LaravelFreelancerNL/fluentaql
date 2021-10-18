<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\Expressions\ExpressionInterface;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class SortClause extends Clause
{
    /**
     * @var array<mixed> $references
     */
    protected $references;

    /**
     * @param array<mixed> $references
     */
    public function __construct(
        array $references
    ) {
        parent::__construct();

        $this->references = $references;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        if (empty($this->references[0])) {
            return 'SORT null';
        }

        /** @var array<string|Expression> $references */
        $references = array_map(function ($reference) use ($queryBuilder) {
            if (!$queryBuilder->grammar->isSortDirection($reference)) {
                return $queryBuilder->normalizeArgument($reference, ['Reference', 'Null', 'Query', 'Bind']);
            }
            return $reference;
        }, $this->references);

        $output = '';
        foreach ($references as $value) {
            if ($value instanceof ExpressionInterface) {
                $output .= ', ' . $value->compile($queryBuilder);
            }
            if (is_string($value)) {
                $output .= ' ' . $value;
            }
        }

        return 'SORT ' . ltrim($output, ', ');
    }
}
