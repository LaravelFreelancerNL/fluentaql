<?php

namespace Tests\Unit\AQL;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

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
}
