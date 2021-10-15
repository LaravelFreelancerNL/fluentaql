<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Geo AQL functions.
 *
 * @see https://www.arangodb.com/docs/stable/aql/functions-geo.html
 */
trait HasGeoFunctions
{
    /**
     * Calculate the distance between two coordinates with the Haversine formula.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-geo.html#distance
     *
     * @param mixed $latitude1
     * @param mixed $longitude1
     * @param mixed $latitude2
     * @param mixed $longitude2
     *
     * @return FunctionExpression
     */
    public function distance($latitude1, $longitude1, $latitude2, $longitude2): FunctionExpression
    {
        return new FunctionExpression('DISTANCE', [$latitude1, $longitude1, $latitude2, $longitude2]);
    }

    /**
     * Return the area in m² for a polygon or multi-polygon on a sphere with the average Earth radius, or an ellipsoid.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_area
     *
     * @param string|array|Object|QueryBuilder|Expression $geoJson
     * @param string|QueryBuilder|Expression $ellipsoid
     * @return FunctionExpression
     */
    public function geoArea(mixed $geoJson, mixed $ellipsoid = "sphere"): FunctionExpression
    {
        return new FunctionExpression('GEO_AREA', [$geoJson, $ellipsoid]);
    }

    /**
     * Checks whether the GeoJSON object geoJsonA fully contains geoJsonB (Every point in B is also in A).
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_contains
     *
     * @param string|array|Object|QueryBuilder|Expression $geoJsonA
     * @param string|array|Object|QueryBuilder|Expression $geoJsonB
     * @return FunctionExpression
     */
    public function geoContains(mixed $geoJsonA, mixed $geoJsonB): FunctionExpression
    {
        return new FunctionExpression('GEO_CONTAINS', [$geoJsonA, $geoJsonB]);
    }

    /**
     * Return the distance between two GeoJSON objects, measured from the centroid of each shape.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_distance
     *
     * @param string|array|Object|QueryBuilder|Expression $geoJsonA
     * @param string|array|Object|QueryBuilder|Expression $geoJsonB
     * @param string|QueryBuilder|Expression $ellipsoid
     * @return FunctionExpression
     */
    public function geoDistance(mixed $geoJsonA, mixed $geoJsonB, mixed $ellipsoid = "sphere"): FunctionExpression
    {
        return new FunctionExpression('GEO_DISTANCE', [$geoJsonA, $geoJsonB, $ellipsoid]);
    }

    /**
     * Checks whether two GeoJSON objects are equal or not.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_equals
     *
     * @param string|array|Object|QueryBuilder|Expression $geoJsonA
     * @param string|array|Object|QueryBuilder|Expression $geoJsonB
     * @return FunctionExpression
     */
    public function geoEquals(mixed $geoJsonA, mixed $geoJsonB): FunctionExpression
    {
        return new FunctionExpression('GEO_EQUALS', [$geoJsonA, $geoJsonB]);
    }

    /**
     * Checks whether the GeoJSON object geoJsonA intersects with geoJsonB
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_intersects
     *
     * @param string|array<mixed>|Object|QueryBuilder|Expression $geoJsonA
     * @param string|array<mixed>|Object|QueryBuilder|Expression $geoJsonB
     * @return FunctionExpression
     */
    public function geoIntersects(mixed $geoJsonA, mixed $geoJsonB): FunctionExpression
    {
        return new FunctionExpression('GEO_INTERSECTS', [$geoJsonA, $geoJsonB]);
    }


    /**
     * Checks whether the distance between two GeoJSON objects lies within a given interval.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_in_range
     *
     * @param array|Object|QueryBuilder|Expression $geoJsonA
     * @param array|Object|QueryBuilder|Expression $geoJsonB
     * @param int|float|QueryBuilder|Expression $low
     * @param int|float|QueryBuilder|Expression $high
     * @param null|bool|QueryBuilder|Expression $includeLow
     * @param null|bool|QueryBuilder|Expression $includeHigh
     * @return FunctionExpression
     */
    public function geoInRange(
        mixed $geoJsonA,
        mixed $geoJsonB,
        mixed $low,
        mixed $high,
        mixed $includeLow = null,
        mixed $includeHigh = null,
    ): FunctionExpression {
        $arguments = [
            "geoJsonA" => $geoJsonA,
            "geoJsonB" => $geoJsonB,
            "low" => $low,
            "high" => $high,
            "includeLow" => $includeLow,
            "includeHigh" => $includeHigh
        ];

        $arguments = $this->unsetNullValues($arguments);

        return new FunctionExpression('GEO_IN_RANGE', $arguments);
    }

    /**
     * Construct a GeoJSON LineString. Needs at least two longitude/latitude pairs.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_linestring
     *
     * @param array<mixed>|QueryBuilder|Expression $points
     * @return FunctionExpression
     */
    public function geoLineString(mixed $points): FunctionExpression
    {
        return new FunctionExpression('GEO_LINESTRING', [$points]);
    }

    /**
     * Construct a GeoJSON MultiLineString.
     * Needs at least two elements consisting valid LineStrings coordinate arrays.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_multilinestring
     *
     * @param array<mixed>|QueryBuilder|Expression $points
     * @return FunctionExpression
     */
    public function geoMultiLineString(mixed $points): FunctionExpression
    {
        return new FunctionExpression('GEO_MULTILINESTRING', [$points]);
    }

    /**
     * Construct a valid GeoJSON Point.
     *
     * https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_multipoint
     */
    public function geoPoint(
        int|float|QueryBuilder|Expression $longitude,
        int|float|QueryBuilder|Expression $latitude
    ): FunctionExpression {
        return new FunctionExpression('GEO_POINT', [$longitude, $latitude]);
    }

    /**
     * Construct a GeoJSON LineString. Needs at least two longitude/latitude pairs.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_multipoint
     *
     * @param array<mixed>|QueryBuilder|Expression $points
     * @return FunctionExpression
     */
    public function geoMultiPoint(mixed $points): FunctionExpression
    {
        return new FunctionExpression('GEO_MULTIPOINT', [$points]);
    }


    /**
     * Construct a GeoJSON Polygon. Needs at least one array representing a loop.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_polygon
     *
     * @param array<mixed>|QueryBuilder|Expression $points
     * @return FunctionExpression
     */
    public function geoPolygon(mixed $points): FunctionExpression
    {
        return new FunctionExpression('GEO_POLYGON', [$points]);
    }

    /**
     * Construct a GeoJSON MultiPolygon. Needs at least two Polygons inside.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-geo.html#geo_polygon
     *
     * @param array<mixed>|QueryBuilder|Expression $points
     * @return FunctionExpression
     */
    public function geoMultiPolygon(mixed $points): FunctionExpression
    {
        return new FunctionExpression('GEO_MULTIPOLYGON', [$points]);
    }
}
