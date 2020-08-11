<?php

namespace LaravelFreelancerNL\FluentAQL\Tests\Unit;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\hasDateFunctions
 */
class DateFunctionsTest extends TestCase
{
    public function testDateNow()
    {
        $functionExpression = (new QueryBuilder())->dateNow();
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_NOW()', (string) $functionExpression);
    }

    public function testDateIso8601()
    {
        $functionExpression = (new QueryBuilder())->dateIso8601(2019, 11, 13, 10, 20, 50, 666);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_ISO8601(2019, 11, 13, 10, 20, 50, 666)', (string) $functionExpression);
    }

    public function testDateIso8601WithDateOnly()
    {
        $functionExpression = (new QueryBuilder())->dateIso8601(2019, 11, 13);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_ISO8601(2019, 11, 13)', (string) $functionExpression);
    }

    public function testDateIso8601DefaultsToCurrentTime()
    {
        $functionExpression = (new QueryBuilder())->dateTimestamp();
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals(26, strlen((string) $functionExpression));
    }

    public function testDateTimestamp()
    {
        $functionExpression = (new QueryBuilder())->dateTimestamp(2019, 11, 13, 10, 20, 50, 666);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_TIMESTAMP(2019, 11, 13, 10, 20, 50, 666)', (string) $functionExpression);
    }

    public function testDateTimestampWithDateOnly()
    {
        $functionExpression = (new QueryBuilder())->dateTimestamp(2019, 11, 13);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_TIMESTAMP(2019, 11, 13)', (string) $functionExpression);
    }

    public function testDateTimestampDefaultsToCurrentTime()
    {
        $functionExpression = (new QueryBuilder())->dateTimestamp();
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals(26, strlen((string) $functionExpression));
    }

    public function testDateYear()
    {
        $functionExpression = (new QueryBuilder())->dateYear(1399472349522);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_YEAR(1399472349522)', (string) $functionExpression);
    }

    public function testDateMonth()
    {
        $functionExpression = (new QueryBuilder())->dateMonth(1399472349522);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_MONTH(1399472349522)', (string) $functionExpression);
    }

    public function testDateDay()
    {
        $functionExpression = (new QueryBuilder())->dateDay(1399472349522);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_DAY(1399472349522)', (string) $functionExpression);
    }

    public function testDateHour()
    {
        $functionExpression = (new QueryBuilder())->dateHour(1399472349522);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_HOUR(1399472349522)', (string) $functionExpression);
    }

    public function testDateMinute()
    {
        $functionExpression = (new QueryBuilder())->dateMinute(1399472349522);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_MINUTE(1399472349522)', (string) $functionExpression);
    }

    public function testDateSecond()
    {
        $functionExpression = (new QueryBuilder())->dateSecond(1399472349522);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_SECOND(1399472349522)', (string) $functionExpression);
    }

    public function testDateMillisecond()
    {
        $functionExpression = (new QueryBuilder())->dateMillisecond(1399472349522);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_MILLISECOND(1399472349522)', (string) $functionExpression);
    }
}
