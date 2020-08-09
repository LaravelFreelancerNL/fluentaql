<?php

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;

/**
 * Geo AQL functions.
 * @see https://www.arangodb.com/docs/stable/aql/functions-geo.html
 */
trait HasGeoFunctions
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
        $latitude1 = $this->normalizeArgument($latitude1, ['Number', 'Reference']);
        $longitude1 = $this->normalizeArgument($longitude1, ['Number', 'Reference']);
        $latitude2 = $this->normalizeArgument($latitude2, ['Number', 'Reference']);
        $longitude2 = $this->normalizeArgument($longitude2, ['Number', 'Reference']);

        return new FunctionExpression('DISTANCE', [$latitude1, $longitude1, $latitude2, $longitude2]);
    }
}
