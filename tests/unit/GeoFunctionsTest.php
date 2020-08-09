<?php

namespace LaravelFreelancerNL\FluentAQL\Tests\Unit;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\hasGeoFunctions
 */
class GeoFunctionsTest extends TestCase
{
    public function testDistance()
    {
        $functionExpression = (new QueryBuilder())->distance(52.5163, 13.3777, 50.9322, 6.94);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DISTANCE(52.5163, 13.3777, 50.9322, 6.94)', (string) $functionExpression);
    }

    public function testDistanceByReference()
    {
        $qb = (new QueryBuilder())->for('l', 'locations')->for('u', 'users');
        $functionExpression = $qb->distance('l.lat', 'l.lon', 'u.lat', 'u.lon');
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DISTANCE(l.lat, l.lon, u.lat, u.lon)', (string) $functionExpression);
    }
}
