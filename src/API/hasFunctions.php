<?php
namespace LaravelFreelancerNL\FluentAQL\API;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;

/**
 * Trait hasFunctions
 *
 * AQL Function API calls.
 *
 * @package LaravelFreelancerNL\FluentAQL\API
 */
trait hasFunctions
{
    use hasArrayFunctions,
        hasDateFunctions,
        hasDocumentFunctions,
        hasGeoFunctions,
        hasNumericFunctions,
        hasMiscellaneousFunctions,
        hasStringFunctions,
        hasTypeFunctions,
        hasFulltextFunctions;

    /**
     * 'Catch all' method for AQL functions that haven't been implemented directly in this builder.
     *
     * @param $functionName
     * @param mixed ...$parameters
     * @return FunctionExpression
     */
    public function function($functionName, ...$parameters)
    {
        //Normalize input

        //Return a Function Expression
        return new FunctionExpression($functionName, $parameters);
    }
}