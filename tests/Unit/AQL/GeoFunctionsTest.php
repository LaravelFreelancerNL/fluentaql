<?php

namespace Tests\Unit\AQL;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use Tests\TestCase;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\HasGeoFunctions
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\normalizesGeoFunctions
 */
class GeoFunctionsTest extends TestCase
{
    public function testDistance()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->distance(52.5163, 13.3777, 50.9322, 6.94));
        self::assertEquals('RETURN DISTANCE(52.5163, 13.3777, 50.9322, 6.94)', $qb->get()->query);
    }

    public function testDistanceByReference()
    {
        $qb = new QueryBuilder();
        $qb->for('l', 'locations')
            ->for('u', 'users')
            ->return($qb->distance('l.lat', 'l.lon', 'u.lat', 'u.lon'));
        self::assertEquals(
            'FOR l IN locations'
            . ' FOR u IN users'
            . ' RETURN DISTANCE(l.lat, l.lon, u.lat, u.lon)',
            $qb->get()->query
        );
    }

    public function testGeoArea()
    {
        $qb = new QueryBuilder();
        $qb->let('polygon', [
            'type' => 'Polygon',
            'coordinates' => [[[-11.5, 23.5], [-10.5, 26.1], [-11.2, 27.1], [-11.5, 23.5]]],
        ])
            ->return($qb->geoArea('polygon', 'wgs84'));

        self::assertEquals(
            'LET polygon = {"type":"Polygon",'
            . '"coordinates":[[[-11.5,23.5],[-10.5,26.1],[-11.2,27.1],[-11.5,23.5]]]}'
            . ' RETURN GEO_AREA(polygon, @'
            . $qb->getQueryId() . '_1)',
            $qb->get()->query
        );
    }

    public function testGeoContains()
    {
        $qb = new QueryBuilder();
        $qb->let('polygon', [
            'type' => 'Polygon',
            'coordinates' => [[[-11.5, 23.5], [-10.5, 26.1], [-11.2, 27.1], [-11.5, 23.5]]],
        ])
            ->for('loc', 'locations')
            ->return($qb->geoContains('polygon', 'loc.address.geometry'));

        self::assertEquals(
            'LET polygon = {"type":"Polygon",'
            . '"coordinates":[[[-11.5,23.5],[-10.5,26.1],[-11.2,27.1],[-11.5,23.5]]]}'
            . ' FOR loc IN locations'
            . ' RETURN GEO_CONTAINS(polygon, loc.address.geometry)',
            $qb->get()->query
        );
    }

    public function testGeoDistance()
    {
        $qb = new QueryBuilder();
        $qb->let('polygon', [
            'type' => 'Polygon',
            'coordinates' => [[[-11.5, 23.5], [-10.5, 26.1], [-11.2, 27.1], [-11.5, 23.5]]],
        ])
            ->for('loc', 'locations')
            ->let('distance', $qb->geoDistance('loc.geometry', 'polygon'))
            ->return('distance');

        self::assertEquals(
            'LET polygon = {"type":"Polygon",'
            . '"coordinates":[[[-11.5,23.5],[-10.5,26.1],[-11.2,27.1],[-11.5,23.5]]]}'
            . ' FOR loc IN locations'
            . ' LET distance = GEO_DISTANCE(loc.geometry, polygon, @'
            . $qb->getQueryId() . '_1)'
            . ' RETURN distance',
            $qb->get()->query
        );
    }

    public function testGeoEquals()
    {
        $qb = new QueryBuilder();
        $qb->let('polygon', [
            'type' => 'Polygon',
            'coordinates' => [[[-11.5, 23.5], [-10.5, 26.1], [-11.2, 27.1], [-11.5, 23.5]]],
        ])
            ->for('loc', 'locations')
            ->return($qb->geoEquals('polygon', 'loc.address.geometry'));

        self::assertEquals(
            'LET polygon = {"type":"Polygon",'
            . '"coordinates":[[[-11.5,23.5],[-10.5,26.1],[-11.2,27.1],[-11.5,23.5]]]}'
            . ' FOR loc IN locations'
            . ' RETURN GEO_EQUALS(polygon, loc.address.geometry)',
            $qb->get()->query
        );
    }

    public function testGeoIntersects()
    {
        $qb = new QueryBuilder();
        $qb->let('polygon', [
            'type' => 'Polygon',
            'coordinates' => [[[-11.5, 23.5], [-10.5, 26.1], [-11.2, 27.1], [-11.5, 23.5]]],
        ])
            ->for('loc', 'locations')
            ->return($qb->geoIntersects('polygon', 'loc.address.geometry'));

        self::assertEquals(
            'LET polygon = {"type":"Polygon",'
            . '"coordinates":[[[-11.5,23.5],[-10.5,26.1],[-11.2,27.1],[-11.5,23.5]]]}'
            . ' FOR loc IN locations'
            . ' RETURN GEO_INTERSECTS(polygon, loc.address.geometry)',
            $qb->get()->query
        );
    }

    public function testGeoInRange()
    {
        $qb = new QueryBuilder();
        $qb->let('polygon', [
            'type' => 'Polygon',
            'coordinates' => [[[-11.5, 23.5], [-10.5, 26.1], [-11.2, 27.1], [-11.5, 23.5]]],
        ])
            ->for('loc', 'locations')
            ->return($qb->geoInRange('polygon', 'loc.address.geometry', 10, 100));

        self::assertEquals(
            'LET polygon = {"type":"Polygon",'
            . '"coordinates":[[[-11.5,23.5],[-10.5,26.1],[-11.2,27.1],[-11.5,23.5]]]}'
            . ' FOR loc IN locations'
            . ' RETURN GEO_IN_RANGE(polygon, loc.address.geometry, 10, 100)',
            $qb->get()->query
        );
    }

    public function testGeoInRangeIncludeEdges()
    {
        $qb = new QueryBuilder();
        $qb->let('polygon', [
            'type' => 'Polygon',
            'coordinates' => [[[-11.5, 23.5], [-10.5, 26.1], [-11.2, 27.1], [-11.5, 23.5]]],
        ])
            ->for('loc', 'locations')
            ->return(
                $qb->geoInRange(
                    'polygon',
                    'loc.address.geometry',
                    10,
                    100,
                    false,
                    true
                )
            );

        self::assertEquals(
            'LET polygon = {"type":"Polygon",'
            . '"coordinates":[[[-11.5,23.5],[-10.5,26.1],[-11.2,27.1],[-11.5,23.5]]]}'
            . ' FOR loc IN locations'
            . ' RETURN GEO_IN_RANGE(polygon, loc.address.geometry, 10, 100, false, true)',
            $qb->get()->query
        );
    }

    public function testGeoLineString()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->geoLineString([
            [35, 10], [45, 45],
        ]));

        self::assertEquals(
            'RETURN GEO_LINESTRING([[35,10],[45,45]])',
            $qb->get()->query
        );
    }

    public function testGeoMultiLineString()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->geoMultiLineString([
            [[100.0, 0.0], [101.0, 1.0]],
            [[102.0, 2.0], [101.0, 2.3]],
        ]));

        self::assertEquals(
            'RETURN GEO_MULTILINESTRING([[[100,0],[101,1]],[[102,2],[101,2.3]]])',
            $qb->get()->query
        );
    }

    public function testGeoMultiPoint()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->geoMultiPoint([[35, 10], [45, 45]]));

        self::assertEquals(
            'RETURN GEO_MULTIPOINT([[35,10],[45,45]])',
            $qb->get()->query
        );
    }

    public function testGeoPoint()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->geoPoint(1.0, 2.0));

        self::assertEquals(
            'RETURN GEO_POINT(1, 2)',
            $qb->get()->query
        );
    }

    public function testGeoPolygon()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->geoPolygon([[0.0, 0.0], [7.5, 2.5], [0.0, 5.0]]));

        self::assertEquals(
            'RETURN GEO_POLYGON([[0,0],[7.5,2.5],[0,5]])',
            $qb->get()->query
        );
    }

    public function testGeoMultiPolygon()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->geoMultiPolygon([
            [
                [[40, 40], [20, 45], [45, 30], [40, 40]],
            ],
            [
                [[20, 35], [10, 30], [10, 10], [30, 5], [45, 20], [20, 35]],
                [[30, 20], [20, 15], [20, 25], [30, 20]],
            ],
        ]));

        self::assertEquals(
            'RETURN GEO_MULTIPOLYGON([[[[40,40],[20,45],[45,30],[40,40]]'
            . '],[[[20,35],[10,30],[10,10],[30,5],[45,20],[20,35]],[[30,20],[20,15],[20,25],[30,20]]]])',
            $qb->get()->query
        );
    }
}
