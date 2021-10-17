<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use phpDocumentor\Reflection\Types\ArrayKey;

class WindowClause extends Clause
{
    /**
     * @var array<array<ArrayKey, string>>
     */
    protected array|object $offsets;

    protected null|string|QueryBuilder|Expression $rangeValue;

    protected string|null $expression;

    /**
     * CollectClause constructor.
     * @param  array<array<ArrayKey, string>>|QueryBuilder|Expression $offsets
     */
    public function __construct(
        mixed $offsets,
        null|string|QueryBuilder|Expression $rangeValue = null
    ) {
        $this->offsets = $offsets;
        $this->rangeValue = $rangeValue;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $this->offsets = $queryBuilder->normalizeArgument(
            $this->offsets,
            ['List', 'Reference', 'Function', 'Query', 'Bind'],
        );

        if (isset($this->rangeValue)) {
            $this->rangeValue = $queryBuilder->normalizeArgument(
                $this->rangeValue,
                ['Reference', 'Function', 'Query', 'Bind'],
            );
        }

        $output = 'WINDOW';
        if (isset($this->rangeValue)) {
            $output .= ' ' . $this->rangeValue->compile($queryBuilder);
            $output .= ' WITH';
        }
        $output .= ' ' . $this->offsets->compile($queryBuilder);

        return $output;
    }
}
