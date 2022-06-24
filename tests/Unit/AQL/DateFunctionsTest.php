<?php

namespace Tests\Unit\AQL;

use DateTime;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\Tests\TestCase;

/**
 * @covers \LaravelFreelancerNL\FluentAQL\AQL\HasDateFunctions
 * @covers \LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\NormalizesDateFunctions
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\NormalizesExpressions
 * @covers \LaravelFreelancerNL\FluentAQL\Traits\NormalizesFunctions
 */
class DateFunctionsTest extends TestCase
{
    public function testDateCompare()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->dateCompare(
            '1985-04-04',
            $qb->dateNow(),
            'months',
            'days'
        ));

        self::assertEquals(
            'RETURN DATE_COMPARE(@'.$qb->getQueryId()
            .'_1, DATE_NOW(), @'
            .$qb->getQueryId().'_2, @'
            .$qb->getQueryId().'_3)',
            $qb->get()->query
        );
    }

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

    public function testDateFormat()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->dateFormat(1399472349522, '%q/%yyyy'));
        self::assertEquals('RETURN DATE_FORMAT(1399472349522, @'.$qb->getQueryId().'_1)', $qb->get()->query);
    }

    public function testDateUtcToLocal()
    {
        $qb = new QueryBuilder();
        $qb->return(
            $qb->dateUtcToLocal(
                '2021-10-16T15:09:00.000Z',
                'Europe/Amsterdam'
            )
        );

        self::assertEquals(
            'RETURN DATE_UTCTOLOCAL(@'.$qb->getQueryId()
            .'_1, @'
            .$qb->getQueryId().'_2)',
            $qb->get()->query
        );
    }

    public function testDateUtcToLocalWithZoneInfo()
    {
        $qb = new QueryBuilder();
        $qb->return(
            $qb->dateUtcToLocal(
                '2021-10-16T15:09:00.000Z',
                'Europe/Amsterdam',
                (object) [
                    'name' => 'UTC',
                    'begin' => null,
                    'end' => null,
                    'dst' => true,
                    'offset' => 0,
                ]
            )
        );

        self::assertEquals(
            'RETURN DATE_UTCTOLOCAL(@'.$qb->getQueryId()
            .'_1, @'
            .$qb->getQueryId().'_2, {"name":"UTC","begin":null,"end":null,"dst":true,"offset":0})',
            $qb->get()->query
        );
    }

    public function testDateLocalToUtc()
    {
        $qb = new QueryBuilder();
        $qb->return(
            $qb->dateLocalToUtc(
                '2021-10-16',
                'Europe/Amsterdam'
            )
        );

        self::assertEquals(
            'RETURN DATE_LOCALTOUTC(@'.$qb->getQueryId()
            .'_1, @'
            .$qb->getQueryId().'_2)',
            $qb->get()->query
        );
    }

    public function testDateLocalToUtcWithZoneInfo()
    {
        $qb = new QueryBuilder();
        $qb->return(
            $qb->dateLocalToUtc(
                '2021-10-16',
                'Europe/Amsterdam',
                (object) [
                    'name' => 'UTC',
                    'begin' => null,
                    'end' => null,
                    'dst' => true,
                    'offset' => 0,
                ]
            )
        );

        self::assertEquals(
            'RETURN DATE_LOCALTOUTC(@'.$qb->getQueryId()
            .'_1, @'
            .$qb->getQueryId().'_2, {"name":"UTC","begin":null,"end":null,"dst":true,"offset":0})',
            $qb->get()->query
        );
    }

    public function testDateTrunc()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->dateTrunc('2017-02-03', 'month'));

        self::assertEquals(
            'RETURN DATE_TRUNC(@'.$qb->getQueryId()
            .'_1, @'
            .$qb->getQueryId().'_2)',
            $qb->get()->query
        );
    }

    public function testDateRound()
    {
        $qb = new QueryBuilder();
        $qb->return($qb->dateRound('2017-02-03', 15, 'minutes'));

        self::assertEquals(
            'RETURN DATE_ROUND(@'.$qb->getQueryId()
            .'_1, 15, @'
            .$qb->getQueryId().'_2)',
            $qb->get()->query
        );
    }

    public function testDateTimeObjectParameter()
    {
        $date = new DateTime('2011-01-01T15:03:01.012345Z');
        $qb = new QueryBuilder();
        $qb->return($qb->dateRound($date, 15, 'minutes'));
        self::assertEquals(
            'RETURN DATE_ROUND("2011-01-01T15:03:01+00:00", 15, @'
            .$qb->getQueryId().'_1)',
            $qb->get()->query
        );
    }
}
