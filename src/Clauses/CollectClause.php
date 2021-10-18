<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use phpDocumentor\Reflection\Types\ArrayKey;

class CollectClause extends Clause
{

    /**
     * @var array<array<string[]>>|array<mixed>
     */
    protected array $groups;

    protected string|null $expression;

    /**
     * CollectClause constructor.
     * @param  array<string[]> $groups
     */
    public function __construct(array $groups = [])
    {
        $this->groups = $groups;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        $groups = [];
        foreach ($this->groups as $key => $group) {
            $groups[$key][0] = $queryBuilder->normalizeArgument(
                $group[0],
                'Variable'
            );
            $queryBuilder->registerVariable($this->groups[$key][0]);

            $groups[$key][1] = $queryBuilder->normalizeArgument(
                $group[1],
                ['Reference', 'Function', 'Query', 'Bind']
            );
        }

        $output = 'COLLECT';
        $groupOutput = '';
        foreach ($groups as $group) {
            if ($groupOutput !== '') {
                $groupOutput .= ',';
            }
            $groupOutput .= ' ' . $group[0]->compile($queryBuilder);
            /** @psalm-suppress PossiblyUndefinedArrayOffset */
            $groupOutput .=  ' = ' . $group[1]->compile($queryBuilder);
        }

        return $output . $groupOutput;
    }
}
