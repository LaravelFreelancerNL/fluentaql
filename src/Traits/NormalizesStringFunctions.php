<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Trait hasFunctions.
 *
 * AQL Function API calls.
 */
trait NormalizesStringFunctions
{
    abstract protected function normalizeNumbers(QueryBuilder $queryBuilder);

    protected function normalizeConcat(QueryBuilder $queryBuilder): void
    {
        $this->normalizeStrings($queryBuilder);
    }

    protected function normalizeConcatSeparator(QueryBuilder $queryBuilder): void
    {
        $this->normalizeStrings($queryBuilder);
    }

    protected function normalizeContains(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Query', 'Reference', 'Bind']
        );

        $this->parameters[1] = $queryBuilder->normalizeArgument(
            $this->parameters[1],
            ['Query', 'Reference', 'Bind']
        );

        $this->parameters[2] = $queryBuilder->normalizeArgument(
            $this->parameters[2],
            ['Boolean', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeLevenshteinDistance(QueryBuilder $queryBuilder): void
    {
        $this->normalizeStrings($queryBuilder);
    }

    protected function normalizeLower(QueryBuilder $queryBuilder): void
    {
        $this->normalizeStrings($queryBuilder);
    }

    protected function normalizeRegexMatches(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Query', 'Reference', 'Bind']
        );

        $this->parameters[1] = $queryBuilder->normalizeArgument(
            $this->parameters[1],
            ['Query', 'Reference', 'Bind']
        );

        $this->parameters[2] = $queryBuilder->normalizeArgument(
            $this->parameters[2],
            ['Boolean', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeRegexReplace(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Query', 'Reference', 'Bind']
        );

        $this->parameters[1] = $queryBuilder->normalizeArgument(
            $this->parameters[1],
            ['Query', 'Reference', 'Bind']
        );

        $this->parameters[2] = $queryBuilder->normalizeArgument(
            $this->parameters[2],
            ['Query', 'Reference', 'Bind']
        );

        $this->parameters[3] = $queryBuilder->normalizeArgument(
            $this->parameters[3],
            ['Boolean', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeRegexSplit(QueryBuilder $queryBuilder): void
    {
        $this->parameters['text'] = $queryBuilder->normalizeArgument(
            $this->parameters['text'],
            ['Query', 'Reference', 'Bind']
        );

        $this->parameters['splitExpression'] = $queryBuilder->normalizeArgument(
            $this->parameters['splitExpression'],
            ['Query', 'Reference', 'Bind']
        );

        $this->parameters['caseInsensitive'] = $queryBuilder->normalizeArgument(
            $this->parameters['caseInsensitive'],
            ['Boolean', 'Query', 'Reference', 'Bind']
        );

        if (isset($this->parameters['limit'])) {
            $this->parameters['limit'] = $queryBuilder->normalizeArgument(
                $this->parameters['limit'],
                ['Number', 'Query', 'Reference', 'Bind']
            );
        }
    }

    protected function normalizeRegexTest(QueryBuilder $queryBuilder): void
    {
        $this->parameters[0] = $queryBuilder->normalizeArgument(
            $this->parameters[0],
            ['Query', 'Reference', 'Bind']
        );

        $this->parameters[1] = $queryBuilder->normalizeArgument(
            $this->parameters[1],
            ['Query', 'Reference', 'Bind']
        );

        $this->parameters[2] = $queryBuilder->normalizeArgument(
            $this->parameters[2],
            ['Boolean', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeSplit(QueryBuilder $queryBuilder): void
    {
        $this->parameters['value'] = $queryBuilder->normalizeArgument(
            $this->parameters['value'],
            ['Query', 'Reference', 'Bind']
        );

        $this->parameters['separator'] = $queryBuilder->normalizeArgument(
            $this->parameters['separator'],
            ['Query', 'Reference', 'Bind']
        );

        if (isset($this->parameters['limit'])) {
            $this->parameters['limit'] = $queryBuilder->normalizeArgument(
                $this->parameters['limit'],
                ['Number', 'Query', 'Reference', 'Bind']
            );
        }
    }

    protected function normalizeSubstitute(QueryBuilder $queryBuilder): void
    {
        $this->parameters['value'] = $queryBuilder->normalizeArgument(
            $this->parameters['value'],
            ['Query', 'Reference', 'Bind']
        );

        $this->parameters['search'] = $queryBuilder->normalizeArgument(
            $this->parameters['search'],
            ['List', 'Reference', 'Bind']
        );

        $this->parameters['replace'] = $queryBuilder->normalizeArgument(
            $this->parameters['replace'],
            ['List', 'Query', 'Reference', 'Bind']
        );

        $this->parameters['limit'] = $queryBuilder->normalizeArgument(
            $this->parameters['limit'],
            ['Number', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeSubstring(QueryBuilder $queryBuilder): void
    {
        $this->parameters['value'] = $queryBuilder->normalizeArgument(
            $this->parameters['value'],
            ['Query', 'Reference', 'Bind']
        );

         $this->parameters['offset'] = $queryBuilder->normalizeArgument(
             $this->parameters['offset'],
             ['Number', 'Query', 'Reference', 'Bind']
         );

         $this->parameters['length'] = $queryBuilder->normalizeArgument(
             $this->parameters['length'],
             ['Number', 'Query', 'Reference', 'Bind']
         );
    }

    protected function normalizeTokens(QueryBuilder $queryBuilder): void
    {
        $this->normalizeStrings($queryBuilder);
    }

    protected function normalizeTrim(QueryBuilder $queryBuilder): void
    {
        $this->parameters['value'] = $queryBuilder->normalizeArgument(
            $this->parameters['value'],
            ['Query', 'Reference', 'Bind']
        );

        $this->parameters['type'] = $queryBuilder->normalizeArgument(
            $this->parameters['type'],
            ['Number', 'Query', 'Reference', 'Bind']
        );
    }

    protected function normalizeUpper(QueryBuilder $queryBuilder): void
    {
        $this->normalizeStrings($queryBuilder);
    }
}
