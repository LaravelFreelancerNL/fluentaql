<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use phpDocumentor\Reflection\Types\ArrayKey;

class CollectClause extends Clause
{

    /**
     * @var array<array<ArrayKey, string>>
     */
    protected array $groups;

    protected string|null $expression;

    /**
     * CollectClause constructor.
     * @param  array<array<ArrayKey, string>> $groups
     */
    public function __construct(array $groups = [])
    {
        $this->groups = $groups;
    }

    public function compile(QueryBuilder $queryBuilder): string
    {
        foreach ($this->groups as $key => $group) {
            $this->groups[$key][0] = $queryBuilder->normalizeArgument(
                $group[0],
                'Variable'
            );
            $this->groups[$key][1] = $queryBuilder->normalizeArgument(
                $group[1],
                ['Reference', 'Function', 'Query', 'Bind']
            );
        }

        $output = 'COLLECT';
        $groupOutput = '';
        foreach ($this->groups as $group) {
            if ($groupOutput !== '') {
                $groupOutput .= ',';
            }
            $groupOutput .= ' ' . $group[0]->compile($queryBuilder)
                . ' = ' . $group[1]->compile($queryBuilder);
        }

        return $output . $groupOutput;
    }
}
