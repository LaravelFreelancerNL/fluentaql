<?php
namespace LaravelFreelancerNL\FluentAQL;

use LaravelFreelancerNL\FluentAQL\API\hasGraphClauses;
use LaravelFreelancerNL\FluentAQL\Clauses\Clause;
use LaravelFreelancerNL\FluentAQL\API\hasQueryClauses;
use LaravelFreelancerNL\FluentAQL\API\hasFunctions;
use LaravelFreelancerNL\FluentAQL\API\hasStatementClauses;
use LaravelFreelancerNL\FluentAQL\Exceptions\BindException;
use LaravelFreelancerNL\FluentAQL\Exceptions\ExpressionTypeException;
use LaravelFreelancerNL\FluentAQL\Expressions\BindExpression;

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
    use hasQueryClauses, hasStatementClauses, hasGraphClauses, hasFunctions;

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
     * @var int
     */
    protected $queryCount = 1;

    /**
     * Bindings for $query
     * @var $binds
     */
    public $binds = [];

    /**
     * List of read/write/exclusive collections necessary for transactions
     *
     * @var array $collections
     */
    public $collections;

    protected $isSubQuery = false;

    public function __construct($queryId = 1)
    {
        $this->grammar = new Grammar();

        $this->queryId = $queryId;
    }

    public function normalizeArgument($argument, $allowedExpressionTypes)
    {
        $expressionType = null;

        $expressionType = $this->determineExpressionType($argument, $allowedExpressionTypes);

        //Handle bindings. Replace argument data with bind variable.
        if ($expressionType == 'bind') {
            $argument = $this->bind($argument);
        }

        if (!isset($expressionType)) {
            throw new ExpressionTypeException("Not a valid expression type.");
        }
        //Return expression
        $expressionClass = '\LaravelFreelancerNL\FluentAQL\Expressions\\' . ucfirst(strtolower($expressionType)) . 'Expression';
        return new $expressionClass($argument);
    }

    protected function normalizeSortExpression($sortExpression = null, $direction = null) : array
    {
        if (is_string($sortExpression)) {
            $sortExpression = [$sortExpression];
            if ($direction) {
                $sortExpression[] = $direction;
            }
            return $sortExpression;
        }
        if (is_array($sortExpression) && ! empty($sortExpression)) {
            $sortExpression[0] = $sortExpression[0];
            if (isset($sortExpression[1]) && ! $this->grammar->is_sortDirection($sortExpression[1])) {
                unset($sortExpression[1]);
            }
            return $sortExpression;
        }

        return ['null'];
    }

    /**
     * Return the first matching expression type for the argument from the allowed types
     *
     * @param string|iterable $argument
     * @param $allowedExpressionTypes
     * @return mixed
     */
    private function determineExpressionType($argument, $allowedExpressionTypes)
    {
        if (is_string($allowedExpressionTypes)) {
            $allowedExpressionTypes = [$allowedExpressionTypes];
        }

        foreach ($allowedExpressionTypes as $allowedExpressionType) {
            $check = 'is_'.$allowedExpressionType;
            if ($this->grammar->$check($argument)) {
                return $allowedExpressionType;
            }
        }
    }

    public function setSubQuery()
    {
        $this->isSubQuery = true;

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

    /**
     * @param mixed $collections
     * @param string $mode
     * @return QueryBuilder
     */
    public function registerCollections($collections, $mode = 'write') : QueryBuilder
    {
        if (! is_array($collections)) {
            $collections = [$collections];
        }

        $this->collections[$mode] = array_unique(array_merge($collections));

        return $this;
    }

    public function bind($data, $to = null, $collection = false) : BindExpression
    {
        $data = $this->grammar->prepareDataToBind($data);

        if ($to == null) {
            $to  = $this->queryId.'_'.(count($this->binds)+1);
        } else {
            if (!$this->grammar->validateBindParameterSyntax($to)) {
                throw new BindException("Invalid bind parameter.");
            }
        }
        $this->binds[$to] = $data;

        $to = $this->grammar->formatBind($to, $collection);

        return new BindExpression($to);
    }

    /**
     * Compile the query with its bindings and collection list.
     *
     * @param QueryBuilder|null $parentQueryBuilder
     * @return mixed
     */
    public function compile() : QueryBuilder
    {
        $this->query = '';

        foreach ($this->commands as $command) {
            $result = $command->compile();
            $this->query .=  ' '.$result;

            if ($command instanceof QueryBuilder) {
                // Extract binds
                $this->binds = array_unique(array_merge($this->binds, $command->binds));

                // Extract collections
                foreach ($command->collections as $mode) {
                    $this->registerCollections($command->collections[$mode], $mode);
                }
            }
        }
        $this->query = trim($this->query);

        if ($this->isSubQuery) {
            $this->query = '('.$this->query.')';
        }

        return $this;
    }

    /**
     * @return QueryBuilder $this
     */
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
