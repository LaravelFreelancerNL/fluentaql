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
    use HasArangoSearchFunctions;
    use HasArrayFunctions;
    use HasDateFunctions;
    use HasDocumentFunctions;
    use HasGeoFunctions;
    use HasMiscellaneousFunctions;
    use HasNumericFunctions;
    use HasStringFunctions;
}
