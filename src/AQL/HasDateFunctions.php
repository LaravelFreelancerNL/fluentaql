<?php

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Trait hasNumericFunctions.
 *
 * Date AQL functions.
 *
 * @see https://www.arangodb.com/docs/stable/aql/functions-date.html
 */
trait HasDateFunctions
{
    /**
     * Check if two partial dates match.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_compare
     */
    public function dateCompare(
        string|int|QueryBuilder|Expression $date1,
        string|int|QueryBuilder|Expression $date2,
        string|QueryBuilder|Expression $unitRangeStart,
        null|string|QueryBuilder|Expression $unitRangeEnd
    ): FunctionExpression {
        $arguments = [
            "date1" => $date1,
            "date2" => $date2,
            "unitRangeStart" => $unitRangeStart,
        ];
        if (isset($unitRangeEnd)) {
            $arguments['unitRangeEnd'] = $unitRangeEnd;
        }

        return new FunctionExpression('DATE_COMPARE', $arguments);
    }

    /**
     * Get the day value of a date.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_day
     *
     * @param  mixed $date
     *
     * @return FunctionExpression
     */
    public function dateDay($date)
    {
        return new FunctionExpression('DATE_DAY', $date);
    }

    /**
     * Get the custom formatted representation of the date.
     *
     * @Link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_format
     *
     * @param  mixed $date
     * @param  mixed $format
     * @return FunctionExpression
     */
    public function dateFormat($date, $format): FunctionExpression
    {
        return new FunctionExpression('DATE_FORMAT', [$date, $format]);
    }

    /**
     * Get the day value of a date.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_hour
     *
     * @param  mixed $date
     *
     * @return FunctionExpression
     */
    public function dateHour($date)
    {
        return new FunctionExpression('DATE_HOUR', $date);
    }

    /**
     * Return an ISO 8601 date time string from date
     * You may enter a unix timestamp or a datestring: year, month, day, hour, minute, second, millisecond
     * Instead of year you can also enter a unix timestamp in which case month and day may be null.
     * https://www.arangodb.com/docs/stable/aql/functions-date.html#date_iso8601.
     *
     * @return FunctionExpression
     */
    public function dateIso8601(): FunctionExpression
    {
        $arguments = func_get_args();
        if (empty($arguments)) {
            $arguments[] = time();
        }

        return new FunctionExpression('DATE_ISO8601', $arguments);
    }

    /**
     * Get the millisecond of the date.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_millisecond
     *
     * @param  mixed  $date
     *
     * @return FunctionExpression
     */
    public function dateMillisecond($date): FunctionExpression
    {
        return new FunctionExpression('DATE_MILLISECOND', $date);
    }

    /**
     * Get the minute value of a date.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_minute
     *
     * @param  mixed $date
     *
     * @return FunctionExpression
     */
    public function dateMinute($date)
    {
        return new FunctionExpression('DATE_MINUTE', $date);
    }

    /**
     * Get the month value of a date.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_month
     *
     * @param  mixed $date
     *
     * @return FunctionExpression
     */
    public function dateMonth($date)
    {
        return new FunctionExpression('DATE_MONTH', $date);
    }

    /**
     * Get the current unix time as numeric timestamp.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_now
     *
     * @return FunctionExpression
     */
    public function dateNow()
    {
        return new FunctionExpression('DATE_NOW', []);
    }

    /**
     * Get the second of the date.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_second
     *
     * @param  mixed $date
     *
     * @return FunctionExpression
     */
    public function dateSecond($date): FunctionExpression
    {
        return new FunctionExpression('DATE_SECOND', $date);
    }

    /**
     * Return an Unix timestamp from a date value
     * You may enter a or a dateString: year, month, day, hour, minute, second, millisecond
     * Instead of year you can also enter a unix timestamp in which case month and day may be null.
     * https://www.arangodb.com/docs/stable/aql/functions-date.html#date_iso8601.
     *
     * @return FunctionExpression
     */
    public function dateTimestamp()
    {
        $arguments = func_get_args();
        if (empty($arguments)) {
            $arguments[] = time();
        }

        return new FunctionExpression('DATE_TIMESTAMP', $arguments);
    }

    /**
     * Truncates the given date after unit and returns the modified date.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_trunc
     */
    public function dateTrunc(
        int|string|QueryBuilder|Expression $date,
        string|QueryBuilder|Expression $unit
    ): FunctionExpression {
        $arguments = [
            "date" => $date,
            "unit" => $unit
        ];

        return new FunctionExpression('DATE_TRUNC', $arguments);
    }

    /**
     * Bin a date/time into a set of equal-distance buckets, to be used for grouping.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_round
     */
    public function dateRound(
        int|string|QueryBuilder|Expression $date,
        int|QueryBuilder|Expression $amount,
        string|QueryBuilder|Expression $unit
    ): FunctionExpression {
        $arguments = [
            "date" => $date,
            "amount" => $amount,
            "unit" => $unit
        ];

        return new FunctionExpression('DATE_ROUND', $arguments);
    }

    /**
     * Converts date assumed in Zulu time (UTC) to the given timezone.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_utctolocal
     */
    public function dateUtcToLocal(
        int|string|QueryBuilder|Expression $date,
        string|QueryBuilder|Expression $timezone,
        null|array|object $zoneInfo = null
    ): FunctionExpression {
        $arguments = [
            "date" => $date,
            "timezone" => $timezone
        ];
        if (isset($zoneInfo)) {
            $arguments['zoneInfo'] = $zoneInfo;
        }

        return new FunctionExpression('DATE_UTCTOLOCAL', $arguments);
    }

    /**
     * Converts date assumed in the given timezone to Zulu time (UTC).
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_localtoutc
     */
    public function dateLocalToUtc(
        int|string|QueryBuilder|Expression $date,
        string|QueryBuilder|Expression $timezone,
        null|array|object $zoneInfo = null
    ): FunctionExpression {
        $arguments = [
            "date" => $date,
            "timezone" => $timezone
        ];
        if (isset($zoneInfo)) {
            $arguments['zoneInfo'] = $zoneInfo;
        }

        return new FunctionExpression('DATE_LOCALTOUTC', $arguments);
    }


    /**
     * Get the year value of a date.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_year
     *
     * @param  mixed $date
     *
     * @return FunctionExpression
     */
    public function dateYear($date)
    {
        return new FunctionExpression('DATE_YEAR', $date);
    }
}
