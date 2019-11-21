<?php

namespace LaravelFreelancerNL\FluentAQL\API;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;

/**
 * Trait hasFunctions.
 *
 * AQL Function API calls.
 */
trait hasFunctions
{
    use hasArrayFunctions,
        hasDateFunctions,
        hasGeoFunctions,
        hasMiscellaneousFunctions,
        hasNumericFunctions;

    protected function function($functionName, ...$parameters)
    {
        return new FunctionExpression($functionName, $parameters);
    }
}
