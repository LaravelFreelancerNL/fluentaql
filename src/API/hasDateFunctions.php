<?php
namespace LaravelFreelancerNL\FluentAQL\API;

use LaravelFreelancerNL\FluentAQL\Expressions\FunctionExpression;

/**
 * Trait hasNumericFunctions
 *
 * Date AQL functions.
 * @see https://www.arangodb.com/docs/stable/aql/functions-date.html
 * @package LaravelFreelancerNL\FluentAQL\API
 */
trait hasDateFunctions
{
    /**
     * Get the current unix time as numeric timestamp.
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_now
     *
     * @return FunctionExpression
     */
    public function dateNow()
    {
        return new FunctionExpression('DATE_NOW', null);
    }

    /**
     * Return an ISO 8601 date time string from date
     * You may enter a unix timestamp or a datestring: year, month, day, hour, minute, second, millisecond
     * Instead of year you can also enter a unix timestamp in which case month and day may be null.
     * https://www.arangodb.com/docs/stable/aql/functions-date.html#date_iso8601
     *
     * @return FunctionExpression
     */
    public function dateIso8601()
    {
        $dateString = $this->processDateString(func_get_args());

        return new FunctionExpression('DATE_ISO8601', $dateString);
    }

    /**
     * Return an Unix timestamp from a date value
     * You may enter a or a dateString: year, month, day, hour, minute, second, millisecond
     * Instead of year you can also enter a unix timestamp in which case month and day may be null.
     * https://www.arangodb.com/docs/stable/aql/functions-date.html#date_iso8601
     *
     * @return FunctionExpression
     */
    public function dateTimestamp()
    {
        $dateString = $this->processDateString(func_get_args());

        return new FunctionExpression('DATE_TIMESTAMP', $dateString);
    }

    protected function processDateString($dateString)
    {
        if (empty($dateString)) {
            $dateString[] = time();
        } else {
            $dateString[0] = $this->normalizeArgument($dateString[0], ['Number', 'Function', 'Object']);
        }

        $elements = sizeOf($dateString);
        if ($elements > 1) {
            for ($i = 1; $i < $elements; $i++) {
                $dateString[$i] = $this->normalizeArgument($dateString[$i], ['Number', 'Function']);
            }
        }

        return $dateString;
    }

    /**
     * Get the year value of a date
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_year
     *
     * @param  $date
     * @return FunctionExpression
     */
    public function dateYear($date)
    {
        $date = $this->normalizeArgument($date, ['Number', 'Function', 'Object']);

        return new FunctionExpression('DATE_YEAR', $date);
    }

    /**
     * Get the month value of a date
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_month
     *
     * @param  $date
     * @return FunctionExpression
     */
    public function dateMonth($date)
    {
        $date = $this->normalizeArgument($date, ['Number', 'Function', 'Object']);

        return new FunctionExpression('DATE_MONTH', $date);
    }

    /**
     * Get the day value of a date
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_day
     *
     * @param  $date
     * @return FunctionExpression
     */
    public function dateDay($date)
    {
        $date = $this->normalizeArgument($date, ['Number', 'Function', 'Object']);

        return new FunctionExpression('DATE_DAY', $date);
    }

    /**
     * Get the day value of a date
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_hour
     *
     * @param  $date
     * @return FunctionExpression
     */
    public function dateHour($date)
    {
        $date = $this->normalizeArgument($date, ['Number', 'Function', 'Object']);

        return new FunctionExpression('DATE_HOUR', $date);
    }

    /**
     * Get the minute value of a date
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_minute
     *
     * @param  $date
     * @return FunctionExpression
     */
    public function dateMinute($date)
    {
        $date = $this->normalizeArgument($date, ['Number', 'Function', 'Object']);

        return new FunctionExpression('DATE_MINUTE', $date);
    }

    /**
     * Get the second of the date
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_second
     *
     * @param  $date
     * @return FunctionExpression
     */
    public function dateSecond($date)
    {
        $date = $this->normalizeArgument($date, ['Number', 'Function', 'Object']);

        return new FunctionExpression('DATE_SECOND', $date);
    }

    /**
     * Get the millisecond of the date
     * @link https://www.arangodb.com/docs/stable/aql/functions-date.html#date_millisecond
     *
     * @param  $date
     * @return FunctionExpression
     */
    public function dateMillisecond($date)
    {
        $date = $this->normalizeArgument($date, ['Number', 'Function', 'Object']);

        return new FunctionExpression('DATE_MILLISECOND', $date);
    }
}
