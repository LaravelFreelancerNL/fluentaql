<?php

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\Facades\AQB;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\API\hasGeoFunctions
 */
class GeoFunctionsTest extends TestCase
{
    public function test_distance()
    {
        $functionExpression = AQB::distance(52.5163, 13.3777, 50.9322, 6.94);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DISTANCE(52.5163, 13.3777, 50.9322, 6.94)', (string) $functionExpression);
    }

    public function test_distance_by_reference()
    {
        $qb = AQB::for('l', 'locations')->for('u', 'users');
        $functionExpression = $qb->distance('l.lat', 'l.lon', 'u.lat', 'u.lon');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DISTANCE(l.lat, l.lon, u.lat, u.lon)', (string) $functionExpression);
    }
}
