<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use phpDocumentor\Reflection\Types\ArrayKey;

class WindowClause extends Clause
{
    /**
     * @var array<array-key, string>|object $offsets
     */
    protected array|object $offsets;

    protected null|string|QueryBuilder|Expression $rangeValue;

    /**
     * CollectClause constructor.
     * @param  array<array-key, string>|object $offsets
     */
    public function __construct(
        array|object $offsets,
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
            /** @phpstan-ignore-next-line */
            $output .= ' ' . $this->rangeValue->compile($queryBuilder);
            $output .= ' WITH';
        }
        $output .= ' ' . $this->offsets->compile($queryBuilder);

        return $output;
    }
}
