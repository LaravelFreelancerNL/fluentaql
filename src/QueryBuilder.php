<?php
namespace LaravelFreelancerNL\FluentAQL;


use LaravelFreelancerNL\FluentAQL\API\hasClauses;
use LaravelFreelancerNL\FluentAQL\API\hasFunctions;
use LaravelFreelancerNL\FluentAQL\API\hasStatements;


class QueryBuilder
{
    use hasStatements, hasClauses, hasFunctions;

    /**
     * The database query grammar instance.
     *
     * @var \LaravelFreelancerNL\FluentAQL\Grammar
     */
    public $grammar;

    /**
      * List of commands to be compiled into a query
      *
      */
    protected $commands = [];

    /**
     * The AQL QueryBuilder
     * @var $query
     */
    public $query;

    /**
     * Bindings for $query
     * @var $bindings
     */
    public $bindings = [];

    /**
     * List of read/write collections necessary for transactions
     *
     * @var $collections
     */
    public $collections = ['read' => [], 'write' => []];

    protected $isSubQuery = false;

    function __construct($isSubQuery = false)
    {
        $this->grammar = new Grammar();

        $this->isSubQuery = $isSubQuery;
    }

    public function setSubQuery($isSubQuery = true)
    {
        $this->isSubQuery = $isSubQuery;

        return $this;
    }

    /**
     * Add an AQL command (statements, clauses, functions and expressions)
     *
     * @param object $command
     */
    public function addCommand($command)
    {
        $this->commands[] = $command;
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
     * Get the last or a specified command
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
     * Compile the query with its bindings and collection list.
     *
     * @return mixed
     */
    public function compile() : array
    {
        $this->query = '';
        foreach ($this->commands as $command) {
            $commandData = $command->compile();

            $this->query .=  ' '.$commandData['query'];
            $this->bindings = array_merge($this->bindings, $commandData['bindings']);
            $this->collections = array_merge_recursive($this->collections, $commandData['collections']);
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

        //FIXME: temporary
        $this->clearCommands();

        return $this;
    }

    public function toAql()
    {
        return $this->compile()['query'];
    }

    function __toString()
    {
        return $this->toAql();
    }
}
