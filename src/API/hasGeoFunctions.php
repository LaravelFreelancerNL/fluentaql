<?php
namespace LaravelFreelancerNL\FluentAQL\API;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;

/**
 * Geo AQL functions.
 * @see https://www.arangodb.com/docs/stable/aql/functions-geo.html
 * @package LaravelFreelancerNL\FluentAQL\API
 */
trait hasGeoFunctions
{
    /**
     * Calculate the distance between two coordinates with the Haversine formula.
     * @link https://www.arangodb.com/docs/stable/aql/functions-geo.html#distance
     *
     * @param $latitude1
     * @param $longitude1
     * @param $latitude2
     * @param $longitude2
     * @return FunctionExpression
     */
    public function distance($latitude1, $longitude1, $latitude2, $longitude2)
    {
        $latitude1 = $this->normalizeArgument($latitude1, ['Number', 'VariableAttribute']);
        $longitude1 = $this->normalizeArgument($longitude1, ['Number', 'VariableAttribute']);
        $latitude2 = $this->normalizeArgument($latitude2, ['Number', 'VariableAttribute']);
        $longitude2 = $this->normalizeArgument($longitude2, ['Number', 'VariableAttribute']);

        return new FunctionExpression('DISTANCE', [$latitude1, $longitude1, $latitude2, $longitude2]);
    }
}
