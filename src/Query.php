<?php
namespace LaravelFreelancerNL\FluentAQL;

use LaravelFreelancerNL\FluentAQL\API\hasClauses;
use LaravelFreelancerNL\FluentAQL\API\hasFunctions;
use LaravelFreelancerNL\FluentAQL\API\hasStatements;

class Query
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
     * The AQL Query
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

    function __construct()
    {
        $this->grammar = new Grammar();
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
     * Compile the query with its bindings and collection list.
     *
     * @return mixed
     */
    public function compile() : array
    {
        foreach ($this->commands as $command) {
            $commandData = $command->compile();

            $this->query .=  ' '.$commandData['query'];
            $this->bindings = array_merge($this->bindings, $commandData['bindings']);
            $this->collections = array_merge_recursive($this->collections, $commandData['collections']);
        }

        $this->query = trim($this->query);

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

    public function toAql()
    {
        return $this->compile()['query'];
    }

}
