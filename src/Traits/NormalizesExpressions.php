<?php
namespace LaravelFreelancerNL\FluentAQL\Traits;

use LaravelFreelancerNL\FluentAQL\Exceptions\ExpressionTypeException;
use LaravelFreelancerNL\FluentAQL\Expressions\BindExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\ExpressionInterface;
use LaravelFreelancerNL\FluentAQL\Expressions\ListExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\NullExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\ObjectExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\PredicateExpression;
use LaravelFreelancerNL\FluentAQL\Expressions\StringExpression;
use LaravelFreelancerNL\FluentAQL\Grammar;

trait NormalizesExpressions
{

    /**
     * The database query grammar instance.
     *
     * @var Grammar
     */
    protected $grammar;

    protected function normalizeArgument($argument, $allowedExpressionTypes = null)
    {
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

        $expressionClass = '\LaravelFreelancerNL\FluentAQL\Expressions\\' . $expressionType . 'Expression';

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

    protected function normalizeSortExpression($sortExpression = null, $direction = null): array
    {
        if (is_string($sortExpression)) {
            $sortExpression = [$sortExpression];
            if ($direction) {
                $sortExpression[] = $direction;
            }

            return $sortExpression;
        }
        if (is_array($sortExpression) && ! empty($sortExpression)) {
            $sortExpression[0] = $this->normalizeArgument($sortExpression[0], 'Reference');
            if (isset($sortExpression[1]) && ! $this->grammar->isSortDirection($sortExpression[1])) {
                unset($sortExpression[1]);
            }

            return $sortExpression;
        }

        return ['null'];
    }

    protected function normalizeEdgeCollections($edgeCollection): array
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
    protected function normalizePredicates($predicates): array
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
            $comparisonOperator = $predicate[1];
        }
        if (isset($predicate[2])) {
            $value = $predicate[2];
        }
        if (isset($predicate[3]) && $this->grammar->isLogicalOperator($predicate[3])) {
            $logicalOperator = $predicate[3];
        }

        // if $rightOperand is empty and $logicalOperator is not a valid operate, then the operation defaults to '=='
        if ($this->grammar->isComparisonOperator($comparisonOperator) && $value == null) {
            $value = 'null';
        }
        if (! $this->grammar->isComparisonOperator($comparisonOperator) && $value == null) {
            $value = $comparisonOperator;
            $comparisonOperator = '==';
        }

        $attribute = $this->normalizeArgument($attribute, ['Reference']);
        $value = $this->normalizeArgument($value);

        $normalizedPredicate[] = new PredicateExpression($attribute, $comparisonOperator, $value, $logicalOperator);

        return $normalizedPredicate;
    }

    /**
     * Return the first matching expression type for the argument from the allowed types.
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
            "This argument, '{$argument}', does not match one of these expression types: "
            . implode(', ', $allowedExpressionTypes)
            . '.'
        );
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

        return new ObjectExpression($this->normalizeIterable((array) $argument, $allowedExpressionTypes));
    }

}