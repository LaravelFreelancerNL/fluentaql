<?php

namespace LaravelFreelancerNL\FluentAQL\Tests\Unit\Functions;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\HasDateFunctions
 */
class DateFunctionsTest extends TestCase
{
    public function testDateNow()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->dateNow());
        self::assertEquals('RETURN DATE_NOW()', $qb->get()->query);
    }

    public function testDateIso8601()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->dateIso8601(2019, 11, 13, 10, 20, 50, 666));
        self::assertEquals('RETURN DATE_ISO8601(2019, 11, 13, 10, 20, 50, 666)', $qb->get()->query);
    }

    public function testDateIso8601WithDateOnly()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->dateIso8601(2019, 11, 13));
        self::assertEquals('RETURN DATE_ISO8601(2019, 11, 13)', $qb->get()->query);
    }

    public function testDateIso8601DefaultsToCurrentTime()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->dateIso8601());
        self::assertEquals(31, strlen($qb->get()->query));
    }

    public function testDateTimestamp()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->dateTimestamp(2019, 11, 13, 10, 20, 50, 666));
        self::assertEquals('RETURN DATE_TIMESTAMP(2019, 11, 13, 10, 20, 50, 666)', $qb->get()->query);
    }

    public function testDateTimestampWithDateOnly()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->dateTimestamp(2019, 11, 13));
        self::assertEquals('RETURN DATE_TIMESTAMP(2019, 11, 13)', $qb->get()->query);
    }

    public function testDateTimestampDefaultsToCurrentTime()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->dateTimestamp());
        self::assertEquals(33, strlen($qb->get()->query));
    }

    public function testDateYear()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->dateYear(1399472349522));
        self::assertEquals('RETURN DATE_YEAR(1399472349522)', $qb->get()->query);
    }

    public function testDateMonth()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->dateMonth(1399472349522));
        self::assertEquals('RETURN DATE_MONTH(1399472349522)', $qb->get()->query);
    }

    public function testDateDay()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->dateDay(1399472349522));
        self::assertEquals('RETURN DATE_DAY(1399472349522)', $qb->get()->query);
    }

    public function testDateHour()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->dateHour(1399472349522));
        self::assertEquals('RETURN DATE_HOUR(1399472349522)', $qb->get()->query);
    }

    public function testDateMinute()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->dateMinute(1399472349522));
        self::assertEquals('RETURN DATE_MINUTE(1399472349522)', $qb->get()->query);
    }

    public function testDateSecond()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->dateSecond(1399472349522));
        self::assertEquals('RETURN DATE_SECOND(1399472349522)', $qb->get()->query);
    }

    public function testDateMillisecond()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->dateMillisecond(1399472349522));
        self::assertEquals('RETURN DATE_MILLISECOND(1399472349522)', $qb->get()->query);
    }
}
