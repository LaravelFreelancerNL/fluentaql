<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\AQL;

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
    use HasTypeFunctions;
}
