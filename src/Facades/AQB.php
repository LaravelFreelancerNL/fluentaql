<?php
namespace LaravelFreelancerNL\FluentAQL\Facades;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
/**
 * Facade for a more fluent access to the ArangoDB Query Builder
 *
 * @method static QueryBuilder setSubQuery()
 *
 * Statements:
 * @method static QueryBuilder for($variableName, $edgeVariableName = null, $pathVariableName = null)
 * @method static QueryBuilder return($expression)
 * @method static QueryBuilder filter($leftOperand, $comparisonOperator = null, $rightOperand = null, $logicalOperator = 'AND')
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
