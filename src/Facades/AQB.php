<?php
namespace LaravelFreelancerNL\FluentAQL\Facades;

use LaravelFreelancerNL\FluentAQL\API\hasStatementClauses;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Facade for a more fluent access to the ArangoDB Query Builder
 *
 *
 * @method static QueryBuilder get()
 * @method static QueryBuilder setSubQuery()
 * @method static QueryBuilder normalizeArgument($argument, $allowedExpressionTypes)
 *
 * @method static QueryBuilder bind($data, $to = null, $collection = false)
 * @method static QueryBuilder getBindings()
 * @method static QueryBuilder prepareDataToBind($data)
 *
 * Query Clauses:
 * @method static QueryBuilder with()
 * @method static QueryBuilder for($variableName, $in)
 * @method static QueryBuilder return($expression)
 * @method static QueryBuilder filter($leftOperand, $comparisonOperator = null, $rightOperand = null, $logicalOperator = 'AND')
 *
 * Statement Clauses:
 * @method static hasStatementClauses let($variableName, $expression)
 *
 * Functions:
 * Miscellaneous functions
 * @method static QueryBuilder document(...$arguments)
 */
class AQB
{

    /**
     * The object instances.
     *
     * @var array
     */
    protected static $instance;

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param  string  $method
     * @param  array   $args
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public static function __callStatic($method, $args)
    {
        self::$instance = new QueryBuilder();

        return self::$instance->$method(...$args);
    }
}
