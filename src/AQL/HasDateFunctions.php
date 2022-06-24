<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\AQL;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;

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
        string|int|object $date1,
        string|int|object $date2,
        string|object $unitRangeStart,
        null|string|object $unitRangeEnd
    ): FunctionExpression {
        $arguments = [
            'date1' => $date1,
            'date2' => $date2,
            'unitRangeStart' => $unitRangeStart,
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
     */
    public function dateDay(
        string|int|object $date
    ): FunctionExpression {
        return new FunctionExpression('DATE_DAY', $date);
    }

    /**
     * Get the custom formatted representation of the date.
     *
     * @Link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_format
     */
    public function dateFormat(
        string|int|object $date,
        string|object $format
    ): FunctionExpression {
        return new FunctionExpression('DATE_FORMAT', [$date, $format]);
    }

    /**
     * Get the day value of a date.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_hour
     */
    public function dateHour(
        string|int|object $date
    ): FunctionExpression {
        return new FunctionExpression('DATE_HOUR', $date);
    }

    /**
     * Return an ISO 8601 date time string from date
     */
    public function dateIso8601(): FunctionExpression
    {
        /** @var array<int|string> $arguments */
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
     */
    public function dateMillisecond(
        string|int|object $date
    ): FunctionExpression {
        return new FunctionExpression('DATE_MILLISECOND', $date);
    }

    /**
     * Get the minute value of a date.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_minute
     */
    public function dateMinute(
        string|int|object $date
    ): FunctionExpression {
        return new FunctionExpression('DATE_MINUTE', $date);
    }

    /**
     * Get the month value of a date.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_month
     */
    public function dateMonth(
        string|int|object $date
    ): FunctionExpression {
        return new FunctionExpression('DATE_MONTH', $date);
    }

    /**
     * Get the current unix time as numeric timestamp.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_now
     */
    public function dateNow(): FunctionExpression
    {
        return new FunctionExpression('DATE_NOW', []);
    }

    /**
     * Get the second of the date.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_second
     */
    public function dateSecond(
        string|int|object $date
    ): FunctionExpression {
        return new FunctionExpression('DATE_SECOND', $date);
    }

    /**
     * Return an Unix timestamp from a date value
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_iso8601.
     */
    public function dateTimestamp(): FunctionExpression
    {
        /** @var array<int|string> $arguments */
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
        int|string|object $date,
        string|object $unit
    ): FunctionExpression {
        $arguments = [
            'date' => $date,
            'unit' => $unit,
        ];

        return new FunctionExpression('DATE_TRUNC', $arguments);
    }

    /**
     * Bin a date/time into a set of equal-distance buckets, to be used for grouping.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_round
     */
    public function dateRound(
        int|string|object $date,
        int|object $amount,
        string|object $unit
    ): FunctionExpression {
        $arguments = [
            'date' => $date,
            'amount' => $amount,
            'unit' => $unit,
        ];

        return new FunctionExpression('DATE_ROUND', $arguments);
    }

    /**
     * Converts date assumed in Zulu time (UTC) to the given timezone.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_utctolocal
     *
     * @param  array<mixed>|object|null  $zoneInfo
     */
    public function dateUtcToLocal(
        int|string|object $date,
        string|object $timezone,
        null|array|object $zoneInfo = null
    ): FunctionExpression {
        $arguments = [
            'date' => $date,
            'timezone' => $timezone,
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
     *
     * @param  array<mixed>|object|null  $zoneInfo
     */
    public function dateLocalToUtc(
        int|string|object $date,
        string|object $timezone,
        null|array|object $zoneInfo = null
    ): FunctionExpression {
        $arguments = [
            'date' => $date,
            'timezone' => $timezone,
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
     */
    public function dateYear(
        string|int|object $date
    ): FunctionExpression {
        return new FunctionExpression('DATE_YEAR', $date);
    }
}
