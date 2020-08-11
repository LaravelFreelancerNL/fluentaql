<?php

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;

/**
 * Trait hasFunctions.
 *
 * AQL Function API calls.
 */
trait HasFunctions
{
    use HasArrayFunctions;
    use HasDateFunctions;
    use HasGeoFunctions;
    use HasMiscellaneousFunctions;
    use HasNumericFunctions;

    protected function function($functionName, ...$parameters)
    {
        return new FunctionExpression($functionName, $parameters);
    }
}
