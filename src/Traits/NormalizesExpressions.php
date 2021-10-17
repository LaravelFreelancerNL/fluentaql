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

    abstract public function bind(mixed $data, string $to = null);

    /**
     * @param null|string[] $allowedExpressionTypes
     * @throws ExpressionTypeException
     */
    public function normalizeArgument(
        mixed $argument,
        array|string $allowedExpressionTypes = null
    ): Expression {
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
     * @param array<mixed>|string|int|float|bool|null $argument
     * @param string[] $allowedExpressionTypes
     * @throws ExpressionTypeException
     */
    protected function normalizeScalar(
        array|string|int|float|bool|null $argument,
        array|string $allowedExpressionTypes = null
    ): Expression {
        $argumentType = $this->determineArgumentType($argument, $allowedExpressionTypes);

        return $this->createExpression($argument, $argumentType);
    }

    protected function createExpression(
        mixed $argument,
        string $argumentType
    ): Expression {
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

    /**
     * @param string[]|null $allowedExpressionTypes
     * @throws ExpressionTypeException
     */
    protected function normalizeCompound(
        mixed $argument,
        array|string $allowedExpressionTypes = null
    ): Expression {
        if (is_array($argument)) {
            return $this->normalizeArray($argument, $allowedExpressionTypes);
        }
        if (!is_iterable($argument)) {
            return $this->normalizeObject($argument, $allowedExpressionTypes);
        }

        return new ObjectExpression($this->normalizeIterable($argument, $allowedExpressionTypes));
    }

    /**
     * @param iterable<mixed> $argument
     * @param string[]|null $allowedExpressionTypes
     * @return iterable<Expression>|Expression
     * @throws ExpressionTypeException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function normalizeIterable(
        iterable $argument,
        array|string $allowedExpressionTypes = null
    ): iterable|Expression {
        foreach ($argument as $attribute => $value) {
            $argument[$attribute] = $this->normalizeArgument($value);
        }

        return $argument;
    }

    /**
     * @param array<mixed>|PredicateExpression $predicates
     * @return array<mixed>|PredicateExpression
     * @throws ExpressionTypeException
     */
    public function normalizePredicates(
        array|PredicateExpression $predicates
    ): array|PredicateExpression {
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
     * @param string[]|null $allowedExpressionTypes
     * @throws ExpressionTypeException
     */
    protected function determineArgumentType(
        mixed $argument,
        array|string $allowedExpressionTypes = null
    ): string {
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
     * @param array<mixed> $argument
     * @param string[] $allowedExpressionTypes
     * @throws ExpressionTypeException
     */
    protected function normalizeArray(
        array $argument,
        array|string $allowedExpressionTypes = null
    ): Expression {
        if ($this->grammar->isAssociativeArray($argument)) {
            return new ObjectExpression($this->normalizeIterable($argument, $allowedExpressionTypes));
        }

        return new ListExpression($this->normalizeIterable($argument, $allowedExpressionTypes));
    }

    /**
     * @param string[] $allowedExpressionTypes
     * @throws ExpressionTypeException
     */
    protected function normalizeObject(
        object $argument,
        array|string $allowedExpressionTypes = null
    ): Expression {
        if ($argument instanceof \DateTimeInterface) {
            return new StringExpression($argument->format(\DateTime::ATOM));
        }

        if ($argument instanceof QueryBuilder) {
            return new QueryExpression($argument);
        }

        return new ObjectExpression($this->normalizeIterable((array) $argument, $allowedExpressionTypes));
    }
}
