<?php

namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\Exceptions\ExpressionTypeException;
use LaravelFreelancerNL\FluentAQL\Expressions\BindExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\Expression;
use LaravelFreelancerNL\FluentAQL\Expressions\ListExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\NullExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\ObjectExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\PredicateExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\QueryExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\StringExpression;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

trait NormalizesExpressions
{

    abstract public function bind($data, $to = null);

    /**
     * @param $argument
     * @param  array|null  $allowedExpressionTypes
     * @return Expression
     * @throws ExpressionTypeException
     */
    public function normalizeArgument($argument, $allowedExpressionTypes = null): Expression|NullExpression
    {
        if ($argument instanceof Expression) {
            return $argument;
        }

        if (is_scalar($argument)) {
            return $this->normalizeScalar($argument, $allowedExpressionTypes);
        }

        if (is_null($argument)) {
            return new NullExpression();
        }

        return $this->normalizeCompound($argument, $allowedExpressionTypes);
    }

    /**
     * @param $argument
     * @param $allowedExpressionTypes
     *
     * @throws ExpressionTypeException
     *
     * @return BindExpression
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
        if ($expressionType == 'CollectionBind') {
            return $this->bindCollection($argument);
        }
        $expressionClass = '\LaravelFreelancerNL\FluentAQL\Expressions\\' . $expressionType . 'Expression';

        return new $expressionClass($argument);
    }

    protected function normalizeCompound($argument, $allowedExpressionTypes = null)
    {
        if (is_array($argument)) {
            return $this->normalizeArray($argument, $allowedExpressionTypes);
        }
        if (!is_iterable($argument)) {
            return $this->normalizeObject($argument, $allowedExpressionTypes);
        }

        return new ObjectExpression($this->normalizeIterable($argument, $allowedExpressionTypes));
    }

    /**
     * @param  array<mixed>|object  $argument
     * @param  array<mixed>|null  $allowedExpressionTypes
     *
     * @return array<mixed>|object
     * @throws ExpressionTypeException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function normalizeIterable($argument, $allowedExpressionTypes = null): object|array
    {
        foreach ($argument as $attribute => $value) {
            $argument[$attribute] = $this->normalizeArgument($value);
        }

        return $argument;
    }

    /**
     * @param array<mixed>|PredicateExpression $predicates
     *
     * @return array|object
     */
    public function normalizePredicates(array|PredicateExpression $predicates)
    {
        if ($this->grammar->isPredicate($predicates)) {
            return $this->normalizePredicate($predicates);
        }

        $normalizedPredicates = [];
        foreach ($predicates as $predicate) {
            $normalizedPredicates[] = $this->normalizePredicates($predicate);
        }

        return $normalizedPredicates;
    }

    /**
     * @param non-empty-array<mixed>|PredicateExpression $predicate
     * @return PredicateExpression
     * @throws ExpressionTypeException
     */
    protected function normalizePredicate(array|PredicateExpression $predicate): PredicateExpression
    {
        if ($predicate instanceof PredicateExpression) {
            return $predicate;
        }

        $leftOperand = $this->normalizeArgument($predicate[0]);

        $comparisonOperator = null;
        if (isset($predicate[1])) {
            $comparisonOperator = $predicate[1];
        }


        $rightOperand = null;
        if (isset($predicate[2])) {
            $rightOperand = $this->normalizeArgument($predicate[2]);
        }

        $logicalOperator = 'AND';
        if (isset($predicate[3]) && $this->grammar->isLogicalOperator($predicate[3])) {
            $logicalOperator = $predicate[3];
        }

        return new PredicateExpression(
            $leftOperand,
            $comparisonOperator,
            $rightOperand,
            $logicalOperator
        );
    }

    /**
     * Return the first matching expression type for the argument from the allowed types.
     *
     * @param string|iterable $argument
     * @param $allowedExpressionTypes
     *
     * @throws ExpressionTypeException
     *
     * @return mixed
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
            $check = 'is' . $allowedExpressionType;
            if ($allowedExpressionType == 'Reference' || $allowedExpressionType == 'RegisteredVariable') {
                if ($this->grammar->$check($argument, $this->variables)) {
                    return $allowedExpressionType;
                }
            }

            if ($this->grammar->$check($argument)) {
                return $allowedExpressionType;
            }
        }

        throw new ExpressionTypeException(
            "This argument, 
            '{$argument}', does not match one of these expression types: "
                . implode(', ', $allowedExpressionTypes)
                . '.'
        );
    }

    /**
     * @param $argument
     * @param $allowedExpressionTypes
     *
     * @return ListExpression|ObjectExpression
     * @throws ExpressionTypeException
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
     *
     * @return Expression
     * @throws ExpressionTypeException
     */
    protected function normalizeObject($argument, $allowedExpressionTypes)
    {
        if ($argument instanceof \DateTimeInterface) {
            return new StringExpression($argument->format(\DateTime::ATOM));
        }

        if ($argument instanceof QueryBuilder) {
            return new QueryExpression($argument);
        }

        return new ObjectExpression($this->normalizeIterable((array) $argument, $allowedExpressionTypes));
    }
}
