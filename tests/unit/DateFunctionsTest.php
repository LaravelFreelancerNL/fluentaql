<?php

use LaravelFreelancerNL\FluentAQL\Exceptions\NotEmptyException as NotEmptyException;
use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\Facades\AQB;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\API\hasDateFunctions
 */
class DateFunctionsTest extends TestCase
{
    public function test_date_now()
    {
        $functionExpression = AQB::dateNow();
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_NOW()', (string) $functionExpression);
    }

    public function test_dateIso8601()
    {
        $functionExpression = AQB::dateIso8601(2019, 11, 13, 10, 20, 50, 666);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_ISO8601(2019, 11, 13, 10, 20, 50, 666)', (string) $functionExpression);
    }

    public function test_dateIso8601_with_date_only()
    {
        $functionExpression = AQB::dateIso8601(2019, 11, 13);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_ISO8601(2019, 11, 13)', (string) $functionExpression);
    }

    public function test_dateIso8601_defaults_to_current_time()
    {
        $functionExpression = AQB::dateTimestamp();
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals(26, strlen((string) $functionExpression));
    }

    public function test_dateTimestamp()
    {
        $functionExpression = AQB::dateTimestamp(2019, 11, 13, 10, 20, 50, 666);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_TIMESTAMP(2019, 11, 13, 10, 20, 50, 666)', (string) $functionExpression);
    }

    public function test_dateTimestamp_with_date_only()
    {
        $functionExpression = AQB::dateTimestamp(2019, 11, 13);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_TIMESTAMP(2019, 11, 13)', (string) $functionExpression);
    }

    public function test_dateTimestamp_defaults_to_current_time()
    {
        $functionExpression = AQB::dateTimestamp();
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals(26, strlen((string) $functionExpression));
    }

    public function test_dateYear()
    {
        $functionExpression = AQB::dateYear(1399472349522);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_YEAR(1399472349522)', (string) $functionExpression);
    }

    public function test_dateMonth()
    {
        $functionExpression = AQB::dateMonth(1399472349522);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_MONTH(1399472349522)', (string) $functionExpression);
    }

    public function test_dateDay()
    {
        $functionExpression = AQB::dateDay(1399472349522);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_DAY(1399472349522)', (string) $functionExpression);
    }

    public function test_dateHour()
    {
        $functionExpression = AQB::dateHour(1399472349522);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_HOUR(1399472349522)', (string) $functionExpression);
    }

    public function test_dateMinute()
    {
        $functionExpression = AQB::dateMinute(1399472349522);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_MINUTE(1399472349522)', (string) $functionExpression);
    }

    public function test_dateSecond()
    {
        $functionExpression = AQB::dateSecond(1399472349522);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_SECOND(1399472349522)', (string) $functionExpression);
    }

    public function test_dateMillisecond()
    {
        $functionExpression = AQB::dateMillisecond(1399472349522);
        self::assertInstanceOf(FunctionExpression::class, $functionExpression);
        self::assertEquals('DATE_MILLISECOND(1399472349522)', (string) $functionExpression);
    }
}
