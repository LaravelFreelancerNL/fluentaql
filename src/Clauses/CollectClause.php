<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Clauses;

use LaravelFreelancerNL\FluentAQL\Exceptions\ExpressionTypeException;
use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class CollectClause extends Clause
{
    /**
     * @var array<array<string[]>>|array<mixed>
     */
    protected array $groups;

    /**
     * CollectClause constructor.
     * @param  array<array<string|null>> $groups
     */
    public function __construct(array $groups = [])
    {
        $this->groups = $groups;
    }

    /**
     * @throws ExpressionTypeException
     */
    public function compile(QueryBuilder $queryBuilder): string
    {
        /** @var array<array<Expression>>  $groups */
        $groups = [];
        /** @var array<int, string|null>  $group */
        foreach ($this->groups as $key => $group) {
            $groups[$key][0] = $queryBuilder->normalizeArgument(
                $group[0],
                'Variable'
            );
            $queryBuilder->registerVariable($groups[$key][0]);

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
