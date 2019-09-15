<?php
namespace LaravelFreelancerNL\FluentAQL\Facades;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;

/**
 * Facade for a more fluent access to the ArangoDB Query Builder
 *
 * @method static QueryBuilder get()
 * @method static QueryBuilder setSubQuery()
 *
 * @method static QueryBuilder bind($data, $to = null, $type = 'variable')
 * @method static QueryBuilder getBindings()
 * @method static QueryBuilder prepareDataToBind($data)
 *
 * Query Clauses:
 * @method static QueryBuilder for($variableName, $edgeVariableName = null, $pathVariableName = null)
 * @method static QueryBuilder return($expression)
 * @method static QueryBuilder filter($leftOperand, $comparisonOperator = null, $rightOperand = null, $logicalOperator = 'AND')
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
