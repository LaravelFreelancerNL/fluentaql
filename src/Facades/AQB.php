<?php
namespace LaravelFreelancerNL\FluentAQL\Facades;

use LaravelFreelancerNL\FluentAQL\QueryBuilder;
use LaravelFreelancerNL\FluentAQL\API\hasGraphClauses;
use LaravelFreelancerNL\FluentAQL\API\hasNumericFunctions;
use LaravelFreelancerNL\FluentAQL\API\hasMiscellaneousFunctions;
use LaravelFreelancerNL\FluentAQL\API\hasQueryClauses;
use LaravelFreelancerNL\FluentAQL\API\hasStatementClauses;

/**
 * Facade for a more fluent access to the ArangoDB Query Builder
 *
 * Query Builder commands:
 * @method static QueryBuilder get()
 * @method static registerCollections($collection, $mode = 'write')
 *
 * @method static QueryBuilder bind($data, $to = null, $collection = false)
 * @method static QueryBuilder getBindings()
 *
 * Query Clauses:
 * @method static hasQueryClauses for($variableName, $in)
 * @method static hasQueryClauses filter($leftOperand, $comparisonOperator = null, $rightOperand = null, $logicalOperator = 'AND')
 * @method static hasQueryClauses collect($variableName = null, $expression = null)
 * @method static hasQueryClauses group($groupsVariable, $projectionExpression = null)
 * @method static hasQueryClauses keep($keepVariable)
 * @method static hasQueryClauses withCount($countVariableName)
 * @method static hasQueryClauses aggregate($variableName, $aggregateExpression)
 * @method static hasQueryClauses sort($sortBy = null, $direction = null)
 * @method static hasQueryClauses limit($offsetOrCount, $count = null)
 * @method static hasQueryClauses return($expression)
 *
 * Statement Clauses:
 * @method static hasStatementClauses let($variableName, $expression)
 * @method static hasStatementClauses insert($document, string $collection)
 * @method static hasStatementClauses update($document, $with, $collection)
 * @method static hasStatementClauses replace($document, $with, string $collection)
 * @method static hasStatementClauses upsert($search, $insert, $with, string $collection, bool $replace = false)
 * @method static hasStatementClauses remove($document, string $collection)
 *
 * Graph Clauses:
 * @method static hasGraphClauses with()
 * @method static hasGraphClauses traverse($fromVertex, $inDirection = 'outbound', $toVertex = null, $kShortestPaths = false)
 * @method static hasGraphClauses shortestPath($fromVertex, $inDirection, $toVertex)
 * @method static hasGraphClauses kShortestPaths($fromVertex, $inDirection, $toVertex)
 * @method static hasGraphClauses graph(string $graphName)
 * @method static hasGraphClauses edgeCollections($edgeCollection)
 * @method static hasGraphClauses prune($attribute, $comparisonOperator = '==', $value = null,  $logicalOperator = 'AND')
 *
 * AQL Functions:
 * Miscellaneous functions:
 * @method static hasMiscellaneousFunctions document($collection, $id = null)
 *
 * Numeric functions:
 * @method static hasNumericFunctions max($value)
 *
 * Supporting clauses:
 * @method static hasQueryClauses raw(string $aql, $bindings = [], $collections = [])
 * @method static hasQueryClauses options($options)
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
