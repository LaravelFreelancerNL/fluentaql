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
use LaravelFreelancerNL\FluentAQL\Expressions\ExpressionInterface;
use LaravelFreelancerNL\FluentAQL\Expressions\ListExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\NullExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\ObjectExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\PredicateExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\StringExpression;

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
     * The AQL query
     * @var $query
     */
    public $query;

    /**
     * Bindings for $query
     * @var $binds
     */
    public $binds = [];

    /**
     * List of read/write/exclusive collections required for transactions
     *
     * @var array $collections
     */
    public $collections;

    /**
     * The database query grammar instance.
     *
     * @var Grammar
     */
    protected $grammar;

    /**
      * List of commands to be compiled into a query
      */
    protected $commands = [];

    /**
     * Registry of variable names used in this query
     */
    protected $variables = [];

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


    protected $isSubQuery = false;



    public function __construct($queryId = 1)
    {
        $this->grammar = new Grammar();

        $this->queryId = $queryId;
    }

    protected function normalizeArgument($argument, $allowedExpressionTypes = null)
    {
        if (is_scalar($argument)) {
            return $this->normalizeScalar($argument, $allowedExpressionTypes);
        }
        if (is_null($argument) && isset($allowedExpressionTypes['null'])) {
            return new NullExpression();
        }
        return $this->normalizeCompound($argument, $allowedExpressionTypes);
    }

    /**
     * @param $argument
     * @param $allowedExpressionTypes
     * @return BindExpression
     * @throws ExpressionTypeException
     */
    protected function normalizeScalar($argument, $allowedExpressionTypes)
    {
        $argumentType = $this->determineArgumentType($argument, $allowedExpressionTypes);

        return $this->createExpression($argument, $argumentType);
    }

    protected function createExpression($argument, $argumentType)
    {
        $expressionType = $this->grammar->mapArgumentTypeToExpressionType($argumentType);

        if ($expressionType == 'Bind') {
            return $this->bind($argument);
        }

        $expressionClass = '\LaravelFreelancerNL\FluentAQL\Expressions\\'.$expressionType.'Expression';
        return new $expressionClass($argument);
    }

    protected function normalizeCompound($argument, $allowedExpressionTypes = null)
    {
        if (is_array($argument)) {
            return $this->normalizeArray($argument, $allowedExpressionTypes);
        }
        if (! is_iterable($argument)) {
            return $this->normalizeObject($argument, $allowedExpressionTypes);
        }
        return new ObjectExpression($this->normalizeIterable($argument, $allowedExpressionTypes));
    }

    /**
     * @param array|object $argument
     * @param null $allowedExpressionTypes
     * @return array
     */
    protected function normalizeIterable($argument, $allowedExpressionTypes = null)
    {
        foreach ($argument as $attribute => $value) {
            $argument[$attribute] = $this->normalizeArgument($value);
        }
        return $argument;
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
            $sortExpression[0] = $this->normalizeArgument($sortExpression[0], 'VariableAttribute');
            if (isset($sortExpression[1]) && ! $this->grammar->isSortDirection($sortExpression[1])) {
                unset($sortExpression[1]);
            }
            return $sortExpression;
        }

        return ['null'];
    }

    protected function normalizeEdgeCollections($edgeCollection) : array
    {
        if (is_string($edgeCollection)) {
            $edgeCollection = [$this->normalizeArgument($edgeCollection, 'Collection')];
            return $edgeCollection;
        }
        if (is_array($edgeCollection) && ! empty($edgeCollection)) {
            $edgeCollection[0] = $this->normalizeArgument($edgeCollection[0], 'Collection');
            if (isset($edgeCollection[1]) && ! $this->grammar->isDirection($edgeCollection[1])) {
                unset($edgeCollection[1]);
            }
            return $edgeCollection;
        }

        return [];
    }

    /**
     * @param array $predicates
     * @return array
     */
    protected function normalizePredicates($predicates) : array
    {
        $normalizedPredicates = [];
        foreach ($predicates as $predicate) {
            if (is_array($predicate[0])) {
                $normalizedPredicates = $this->normalizePredicates($predicate);
            }
            $normalizedPredicates[] = $this->normalizePredicate($predicate);
        }

        return $normalizedPredicates;
    }

    protected function normalizePredicate($predicate)
    {
        $normalizedPredicate = [];
        $comparisonOperator = '==';
        $value = null;
        $logicalOperator = 'AND';

        $attribute = $predicate[0];
        if (isset($predicate[1])) {
            $comparisonOperator = strtoupper($predicate[1]);
        }
        if (isset($predicate[2])) {
            $value  = $predicate[2];
        }
        if (isset($predicate[3]) && $this->grammar->isLogicalOperator(strtoupper($predicate[3]))) {
            $logicalOperator  = strtoupper($predicate[3]);
        }

        // if $rightOperand is empty and $logicalOperator is not a valid operate, then the operation defaults to '=='
        if ($this->grammar->isComparisonOperator($comparisonOperator) && $value == null) {
            $value = 'null';
        }
        if (! $this->grammar->isComparisonOperator($comparisonOperator) && $value == null) {
            $value = $comparisonOperator;
            $comparisonOperator = '==';
        }

        $attribute = $this->normalizeArgument($attribute, ['VariableAttribute']);
        $value = $this->normalizeArgument($value);

        $normalizedPredicate[] = new PredicateExpression($attribute, $comparisonOperator, $value, $logicalOperator);

        return $normalizedPredicate;
    }

    /**
     * Return the first matching expression type for the argument from the allowed types
     *
     * @param string|iterable $argument
     * @param $allowedExpressionTypes
     * @return mixed
     * @throws ExpressionTypeException
     */
    protected function determineArgumentType($argument, $allowedExpressionTypes = null)
    {
        if (is_string($allowedExpressionTypes)) {
            $allowedExpressionTypes = [$allowedExpressionTypes];
        }
        if ($allowedExpressionTypes == null) {
            $allowedExpressionTypes = $this->grammar->getAllowedExpressionTypes();
        }

        foreach ($allowedExpressionTypes as $allowedExpressionType) {
            $check = 'is'.$allowedExpressionType;
            if ($allowedExpressionType == 'VariableAttribute') {
                if ($this->grammar->$check($argument, $this->variables)) {
                    return $allowedExpressionType;
                }
            }
            if ($allowedExpressionType == 'RegisteredVariable') {
                if ($this->grammar->$check($argument, $this->variables)) {
                    return $allowedExpressionType;
                }
            }
            if ($this->grammar->$check($argument)) {
                return $allowedExpressionType;
            }
        }

        //Fallback to BindExpression if allowed
        if (isset($allowedExpressionTypes['Bind'])) {
            return 'Bind';
        }

        throw new ExpressionTypeException("This argument, '{$argument}', does not match one of these expression types: ".implode(', ', $allowedExpressionTypes).'.');
    }

    protected function setSubQuery()
    {
        $this->isSubQuery = true;

        return $this;
    }

    /**
     * Add an AQL command (raw AQL and clauses
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

    /**
     * Register variables on declaration for later data normalization.
     *
     * @param string $variableName
     * @return QueryBuilder
     */
    protected function registerVariable(string $variableName) : QueryBuilder
    {
        $this->variables[$variableName] = $variableName;

        return $this;
    }

    public function bind($data, $to = null, $collection = false) : BindExpression
    {
        $data = json_encode($data, JSON_UNESCAPED_SLASHES);

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
        return $this->get()->query;
    }

    public function __toString()
    {
        return $this->toAql();
    }

    public function wrap($value)
    {
        return $this->grammar->wrap($value);
    }

    /**
     * @param $argument
     * @param $allowedExpressionTypes
     * @return ListExpression|ObjectExpression
     */
    protected function normalizeArray($argument, $allowedExpressionTypes)
    {
        if ($this->grammar->isAssociativeArray($argument)) {
            return new ObjectExpression($this->normalizeIterable($argument, $allowedExpressionTypes));
        }
        return new ListExpression($this->normalizeIterable($argument, $allowedExpressionTypes));
    }

    /**
     * @param $argument
     * @param $allowedExpressionTypes
     * @return ObjectExpression|StringExpression
     */
    protected function normalizeObject($argument, $allowedExpressionTypes)
    {
        if ($argument instanceof \DateTimeInterface) {
            return new StringExpression($argument->format(\DateTime::ATOM));
        }
        if ($argument instanceof ExpressionInterface) {
            //Fixme: check for queryBuilders, functions, binds etc and handle them accordingly
            return $argument;
        }
        return new ObjectExpression($this->normalizeIterable((array)$argument, $allowedExpressionTypes));
    }
}
