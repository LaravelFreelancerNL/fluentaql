<?php
namespace LaravelFreelancerNL\FluentAQL;

use LaravelFreelancerNL\FluentAQL\Clauses\Clause;
use LaravelFreelancerNL\FluentAQL\API\hasClauses;
use LaravelFreelancerNL\FluentAQL\API\hasFunctions;
use LaravelFreelancerNL\FluentAQL\API\hasStatements;
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
    use hasStatements, hasClauses, hasFunctions;

    /**
     * The database query grammar instance.
     *
     * @var \LaravelFreelancerNL\FluentAQL\Grammar
     */
    protected $grammar;

    /**
      * List of clauses to be compiled into a query
      *
      */
    protected $clauses = [];

    /**
     * The AQL query
     * @var $query
     */
    protected $query;

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
    protected $bindings = [];

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

    function __construct($isSubQuery = false, $queryId = 1)
    {
        $this->grammar = new Grammar();

        $this->isSubQuery = $isSubQuery;

        $this->queryId = $queryId;
    }

    public function setSubQuery($isSubQuery = true)
    {
        $this->isSubQuery = $isSubQuery;

        return $this;
    }

    /**
     * Add an AQL command (statements, clauses, functions and expressions)
     *
     * @param Clause $clause
     */
    public function addClause(Clause $clause)
    {
        $this->clauses[] = $clause;
    }

    /**
     * Get the clause list.
     * @return mixed
     */
    public function getClauses()
    {
        return $this->clauses;
    }

    /**
     * Get the last or a specific clause
     * @param int|null $index
     * @return mixed
     */
    public function getClause(int $index = null)
    {
        if ($index === null) {
            return end($this->clauses);
        }
        return $this->clauses[$index];
    }

    /**
     * Remove the last or a specified command
     *
     * @param null $index
     * @return bool
     */
    public function removeClause($index = null) : bool
    {
        if ($index === null) {
            return (array_pop($this->clauses)) ? true : false;
        }
        if (isset($this->clauses[$index])) {
            unset($this->clauses[$index]);
            return true;
        }
        return false;
    }

    /**
     * Clear all commands
     */
    public function clearClauses()
    {
        $this->clauses = [];
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
    public function compile(QueryBuilder $parentQueryBuilder = null) : array
    {
        $this->query = '';
        foreach ($this->clauses as $clause) {
            $compiledData = $clause->compile($this);

            $this->query .=  ' '.$compiledData['query'];
            $this->bindings = array_merge($this->bindings, $compiledData['bindings']);
            $this->collections = array_merge_recursive($this->collections, $compiledData['collections']);
        }

        $this->query = trim($this->query);
        if ($this->isSubQuery) {
            $this->query = '('.$this->query.')';
        }

        return [
            'query' => $this->query,
            'bindings' => $this->bindings,
            'collections' => $this->collections
        ];
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

    function __toString()
    {
        return $this->toAql();
    }
}
