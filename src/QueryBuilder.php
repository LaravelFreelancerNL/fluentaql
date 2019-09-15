<?php
namespace LaravelFreelancerNL\FluentAQL;

use LaravelFreelancerNL\FluentAQL\Clauses\Clause;
use LaravelFreelancerNL\FluentAQL\API\hasQueryClauses;
use LaravelFreelancerNL\FluentAQL\API\hasFunctions;
use LaravelFreelancerNL\FluentAQL\API\hasStatements;
use LaravelFreelancerNL\FluentAQL\Clauses\RawClause;
use LaravelFreelancerNL\FluentAQL\Expressions\BindingExpression;

/**
 * Class QueryBuilder
 * Fluent ArangoDB AQL Query Builder.
 * Creates and compiles AQL queries. Returns all data necessary to run the query,
 * including bindings and a list of used read/write collections.
 *
 * @package LaravelFreelancerNL\FluentAQL
 */
class QueryBuilder
{
    use hasQueryClauses, hasFunctions;

    /**
     * The database query grammar instance.
     *
     * @var Grammar
     */
    protected $grammar;

    /**
      * List of commands to be compiled into a query
      *
      */
    protected $commands = [];

    /**
     * The AQL query
     * @var $query
     */
    public $query;

    /**
     * ID of the query
     * Used as prefix for automatically generated bindings.
     * @var int $bindPrefix
     */
    protected $queryId = 1;

    /**
     * Total number of (sub)queries, including this one.
     *
     * @var int
     */
    protected $queryCount = 1;

    /**
     * Bindings for $query
     * @var $bindings
     */
    public $bindings = [];

    /**
     * Prefix for collections
     *
     * @var string
     */
    protected $collectionPrefix = '';

    /**
     * List of read/write collections necessary for transactions
     *
     * @var $collections
     */
    public $collections = ['read' => [], 'write' => []];

    protected $isSubQuery = false;

    public function __construct($queryId = 1)
    {
        $this->grammar = new Grammar();

        $this->queryId = $queryId;
    }

    public function setSubQuery($isSubQuery = true)
    {
        $this->isSubQuery = $isSubQuery;

        return $this;
    }

    /**
     * Add an AQL command (raw AQL, clauses, query builders and expressions)
     *
     * @param Clause|QueryBuilder $clause
     */
    public function addCommand($clause)
    {
        $this->commands[] = $clause;
    }

    /**
     * Get the command list.
     * @return mixed
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * Get the last or a specific command
     * @param int|null $index
     * @return mixed
     */
    public function getCommand(int $index = null)
    {
        if ($index === null) {
            return end($this->commands);
        }
        return $this->commands[$index];
    }

    /**
     * Remove the last or a specified command
     *
     * @param null $index
     * @return bool
     */
    public function removeCommand($index = null) : bool
    {
        if ($index === null) {
            return (array_pop($this->commands)) ? true : false;
        }
        if (isset($this->commands[$index])) {
            unset($this->commands[$index]);
            return true;
        }
        return false;
    }

    /**
     * Clear all commands
     */
    public function clearCommands()
    {
        $this->commands = [];
    }

    public function bind($data, $to = null, $type = 'variable')
    {
        $data = $this->grammar->prepareDataToBind($data);


        if ($to == null) {
            $to  = count($this->bindings) + 1;
        } else {
            if (!$this->grammar->validateBindParameterSyntax($to)) {
                throw new \Exception("Invalid bind parameter.");
            }
        }
        $this->bindings[$to] = $data;

        return new BindingExpression($to, $type);
    }

    public function getBindings()
    {
        return $this->bindings;
    }

    /**
     * Compile the query with its bindings and collection list.
     *
     * @param QueryBuilder|null $parentQueryBuilder
     * @return mixed
     */
    public function compile(QueryBuilder $parentQueryBuilder = null) : string
    {
        $this->query = '';
        foreach ($this->commands as $command) {
            $this->query .=  $command->compile($this);
        }

        if ($this->isSubQuery) {
            $this->query = '('.$this->query.')';
        }

        return $this->query;
    }

    public function get()
    {
        $this->compile();

        return $this;
    }

    /**
     * @return string
     */
    public function toAql()
    {
        return $this->compile()['query'];
    }

    public function __toString()
    {
        return $this->toAql();
    }
}
