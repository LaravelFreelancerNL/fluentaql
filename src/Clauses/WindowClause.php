<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Exceptions\ExpressionTypeException;
use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class WindowClause extends Clause
{
    /**
     * @var array<array-key, string>|object
     */
    protected array|object $offsets;

    protected null|string|QueryBuilder|Expression $rangeValue;

    /**
     * CollectClause constructor.
     *
     * @param  array<array-key, string>|object  $offsets
     */
    public function __construct(
        array|object $offsets,
        string|QueryBuilder|Expression $rangeValue = null
    ) {
        parent::__construct();

        $this->offsets = $offsets;
        $this->rangeValue = $rangeValue;
    }

    /**
     * @throws ExpressionTypeException
     */
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
