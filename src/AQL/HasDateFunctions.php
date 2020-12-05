<?php

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
     * Return an ISO 8601 date time string from date
     * You may enter a unix timestamp or a datestring: year, month, day, hour, minute, second, millisecond
     * Instead of year you can also enter a unix timestamp in which case month and day may be null.
     * https://www.arangodb.com/docs/stable/aql/functions-date.html#date_iso8601.
     *
     * @return FunctionExpression
     */
    public function dateIso8601()
    {
        $arguments = func_get_args();
        if (empty($arguments)) {
            $arguments[] = time();
        }

        return new FunctionExpression('DATE_ISO8601', $arguments);
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
     * Get the year value of a date.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_year
     *
     * @param  $date
     *
     * @return FunctionExpression
     */
    public function dateYear($date)
    {
        return new FunctionExpression('DATE_YEAR', $date);
    }

    /**
     * Get the month value of a date.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_month
     *
     * @param  $date
     *
     * @return FunctionExpression
     */
    public function dateMonth($date)
    {
        return new FunctionExpression('DATE_MONTH', $date);
    }

    /**
     * Get the day value of a date.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_day
     *
     * @param  $date
     *
     * @return FunctionExpression
     */
    public function dateDay($date)
    {
        return new FunctionExpression('DATE_DAY', $date);
    }

    /**
     * Get the day value of a date.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_hour
     *
     * @param  $date
     *
     * @return FunctionExpression
     */
    public function dateHour($date)
    {
        return new FunctionExpression('DATE_HOUR', $date);
    }

    /**
     * Get the minute value of a date.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_minute
     *
     * @param  $date
     *
     * @return FunctionExpression
     */
    public function dateMinute($date)
    {
        return new FunctionExpression('DATE_MINUTE', $date);
    }

    /**
     * Get the second of the date.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_second
     *
     * @param  $date
     *
     * @return FunctionExpression
     */
    public function dateSecond($date)
    {
        return new FunctionExpression('DATE_SECOND', $date);
    }

    /**
     * Get the millisecond of the date.
     *
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_millisecond
     *
     * @param  $date
     *
     * @return FunctionExpression
     */
    public function dateMillisecond($date)
    {
        return new FunctionExpression('DATE_MILLISECOND', $date);
    }

    /**
     * Get the custom formatted representation of the date.
     *
     * @Link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_format
     *
     * @param  $date
     * @param  $format
     * @return FunctionExpression
     */
    public function dateFormat($date, $format): FunctionExpression
    {
        return new FunctionExpression('DATE_FORMAT', [$date, $format]);
    }
}
