<?php

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Trait hasFunctions.
 *
 * AQL Function API calls.
 */
trait NormalizesFunctions
{
    use NormalizesArangoSearchFunctions;
    use NormalizesArrayFunctions;
    use NormalizesDateFunctions;
    use NormalizesDocumentFunctions;
    use NormalizesGeoFunctions;
    use NormalizesMiscellaneousFunctions;
    use NormalizesNumericFunctions;
    use NormalizesStringFunctions;
    use NormalizesTypeFunctions;

    protected function normalizeAny(QueryBuilder $queryBuilder)
    {
        foreach ($this->parameters as $key => $parameter) {
            $this->parameters[$key] = $queryBuilder->normalizeArgument(
                $parameter,
                ['Object', 'List', 'Null', 'Number', 'Boolean', 'List', 'Query', 'Reference', 'Bind']
            );
        }
    }

    protected function normalizeArrays(QueryBuilder $queryBuilder)
    {
        foreach ($this->parameters as $key => $parameter) {
            $this->parameters[$key] = $queryBuilder->normalizeArgument(
                $parameter,
                ['List', 'Query', 'Variable', 'Reference', 'Bind']
            );
        }
    }

    protected function normalizeDocuments(QueryBuilder $queryBuilder)
    {
        foreach ($this->parameters as $key => $parameter) {
            $this->parameters[$key] = $queryBuilder->normalizeArgument(
                $parameter,
                ['Object', 'Query', 'Variable', 'Reference', 'Bind']
            );
        }
    }


    protected function normalizeNumbers(QueryBuilder $queryBuilder)
    {
        foreach ($this->parameters as $key => $parameter) {
            $this->parameters[$key] = $queryBuilder->normalizeArgument(
                $parameter,
                ['Number', 'Function', 'Query', 'Reference', 'Bind']
            );
        }
    }

    protected function normalizeStrings(QueryBuilder $queryBuilder)
    {
        foreach ($this->parameters as $key => $parameter) {
            $this->parameters[$key] = $queryBuilder->normalizeArgument(
                $parameter,
                ['Query', 'Reference', 'Bind']
            );
        }
    }
}
