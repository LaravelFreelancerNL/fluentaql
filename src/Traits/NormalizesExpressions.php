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
use LaravelFreelancerNL\FluentAQL\Grammar;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

trait NormalizesExpressions
{
    /**
     * The database query grammar instance.
     *
     * @var Grammar
     */
    protected $grammar;

    /**
     * @param $argument
     * @param  array|null  $allowedExpressionTypes
     * @return Expression
     * @throws ExpressionTypeException
     */
    public function normalizeArgument($argument, $allowedExpressionTypes = null)
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
     * @param array|object $argument
     * @param null         $allowedExpressionTypes
     *
     * @return array
     */
    protected function normalizeIterable($argument, $allowedExpressionTypes = null)
    {
        foreach ($argument as $attribute => $value) {
            $argument[$attribute] = $this->normalizeArgument($value);
        }

        return $argument;
    }

    public function normalizeSortExpression($sortExpression = null, $direction = null): array
    {
        if (is_string($sortExpression)) {
            $sortExpression = [$sortExpression];
            if ($direction) {
                $sortExpression[] = $direction;
            }

            return $sortExpression;
        }
        if (is_array($sortExpression) && !empty($sortExpression)) {
            $sortExpression[0] = $this->normalizeArgument($sortExpression[0], 'Reference');
            if (isset($sortExpression[1]) && !$this->grammar->isSortDirection($sortExpression[1])) {
                unset($sortExpression[1]);
            }

            return $sortExpression;
        }

        return ['null'];
    }

    /**
     * @param array $predicates
     *
     * @return array
     */
    public function normalizePredicates($predicates): array
    {
        $normalizedPredicates = [];
        if (isset($predicates[1]) && is_string($predicates[1])) {
            $normalizedPredicates[] = $this->normalizePredicate($predicates);
            return $normalizedPredicates;
        }

        foreach ($predicates as $predicate) {
            if ($predicate instanceof PredicateExpression) {
                $normalizedPredicates[] = $predicate;
                continue;
            }
            $normalizedPredicates[] = $this->normalizePredicates($predicate);
        }

        return $normalizedPredicates;
    }

    protected function normalizePredicate($predicate)
    {
        $normalizedPredicate = [];

        if (! $predicate[0] instanceof PredicateExpression) {
            $leftOperand = $this->normalizeArgument($predicate[0]);
        }

        $comparisonOperator = '==';
        if ($this->grammar->isComparisonOperator($predicate[1])) {
            $comparisonOperator = $predicate[1];
        }

        if (! $predicate[2] instanceof PredicateExpression) {
            $rightOperand = $this->normalizeArgument($predicate[2]);
        }

        $logicalOperator = 'AND';

        if (isset($predicate[3]) && $this->grammar->isLogicalOperator($predicate[3])) {
            $logicalOperator = $predicate[3];
        }

        $normalizedPredicate[] = new PredicateExpression(
            $leftOperand,
            $comparisonOperator,
            $rightOperand,
            $logicalOperator
        );

        return $normalizedPredicate;
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

        //Fallback to BindExpression if allowed
        if (isset($allowedExpressionTypes['Bind'])) {
            return 'Bind';
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
