<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Traits;

use DateTimeInterface;
use LaravelFreelancerNL\FluentAQL\Exceptions\BindException;
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
    /**
     * @param  object|array<mixed>|string|int|float|bool|null  $data
     */
    abstract public function bind(
        object|array|string|int|float|bool|null $data,
        string $to = null
    ): BindExpression;

    /**
     * @param  null|string[]|string  $allowedExpressionTypes
     *
     * @throws ExpressionTypeException|BindException
     */
    public function normalizeArgument(
        mixed $argument,
        array|string $allowedExpressionTypes = null
    ): Expression {
        if ($argument instanceof Expression) {
            return $this->processBindExpression($argument);
        }

        if (is_scalar($argument)) {
            return $this->normalizeScalar($argument, $allowedExpressionTypes);
        }

        if (is_null($argument)) {
            return new NullExpression();
        }

        /** @var array<mixed>|object $argument */
        return $this->normalizeCompound($argument, $allowedExpressionTypes);
    }

    /**
     * @param  array<mixed>|string|int|float|bool  $argument
     * @param  array<string>|string|null  $allowedExpressionTypes
     *
     * @throws ExpressionTypeException|BindException
     */
    protected function normalizeScalar(
        array|string|int|float|bool $argument,
        array|string $allowedExpressionTypes = null
    ): Expression {
        $argumentType = $this->determineArgumentType($argument, $allowedExpressionTypes);

        return $this->createExpression($argument, $argumentType);
    }

    /**
     * @psalm-suppress MoreSpecificReturnType
     *
     * @param  array<array-key, mixed>|object|scalar  $argument
     *
     * @throws BindException
     */
    protected function createExpression(
        array|object|bool|float|int|string $argument,
        string $argumentType
    ): Expression {
        $expressionType = $this->grammar->mapArgumentTypeToExpressionType($argumentType);
        if ($expressionType == 'Bind') {
            return $this->bind($argument);
        }
        if ($expressionType == 'CollectionBind') {
            return $this->bindCollection($argument);
        }
        $expressionClass = '\LaravelFreelancerNL\FluentAQL\Expressions\\'.$expressionType.'Expression';

        /** @phpstan-ignore-next-line */
        return new $expressionClass($argument);
    }

    /**
     * @param  array<mixed>|object  $argument
     * @param  array<string>|string|null  $allowedExpressionTypes
     *
     * @throws ExpressionTypeException
     * @throws BindException
     */
    protected function normalizeCompound(
        array|object $argument,
        array|string $allowedExpressionTypes = null
    ): Expression {
        if (is_array($argument)) {
            return $this->normalizeArray($argument, $allowedExpressionTypes);
        }
        if (! is_iterable($argument)) {
            return $this->normalizeObject($argument, $allowedExpressionTypes);
        }

        return new ObjectExpression($this->normalizeIterable((array) $argument, $allowedExpressionTypes));
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param  array<mixed>  $argument
     * @param  array<string>|string|null  $allowedExpressionTypes
     * @return array<array-key, Expression>
     *
     * @throws ExpressionTypeException|BindException
     */
    protected function normalizeIterable(
        array $argument,
        array|string $allowedExpressionTypes = null
    ): array {
        $result = [];
        /** @var mixed $value */
        foreach ($argument as $attribute => $value) {
            /** @var Expression $argument[$attribute] */
            $result[$attribute] = $this->normalizeArgument($value);
        }

        return $result;
    }

    /**
     * @param  array<array-key, mixed>|PredicateExpression  $predicates
     * @return array<array-key, mixed>|PredicateExpression
     *
     * @throws ExpressionTypeException
     * @throws BindException
     */
    public function normalizePredicates(
        array|PredicateExpression $predicates
    ): array|PredicateExpression {
        if ($this->grammar->isPredicate($predicates)) {
            return $this->normalizePredicate($predicates);
        }

        $normalizedPredicates = [];
        if (is_iterable($predicates)) {
            /** @var array<array-key, mixed> $predicate */
            foreach ($predicates as $predicate) {
                $normalizedPredicates[] = $this->normalizePredicates($predicate);
            }
        }

        return $normalizedPredicates;
    }

    /**
     * @param  array<mixed>|PredicateExpression  $predicate
     *
     * @throws ExpressionTypeException|BindException
     */
    protected function normalizePredicate(array|PredicateExpression $predicate): PredicateExpression
    {
        if ($predicate instanceof PredicateExpression) {
            return $predicate;
        }

        $leftOperand = $this->normalizeArgument($predicate[0]);

        $comparisonOperator = null;
        if (isset($predicate[1])) {
            $comparisonOperator = (string) $predicate[1];
        }

        $rightOperand = null;
        if (isset($predicate[2])) {
            $rightOperand = $this->normalizeArgument($predicate[2]);
        }

        $logicalOperator = 'AND';
        if (
            isset($predicate[3])
            && isStringable($predicate[3])
            && $this->grammar->isLogicalOperator((string) $predicate[3])
        ) {
            $logicalOperator = (string) $predicate[3];
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
     * @psalm-suppress MixedArgumentTypeCoercion
     *
     * @param  array<string>|string|null  $allowedExpressionTypes
     *
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

        /** @var string $allowedExpressionType */
        foreach ($allowedExpressionTypes as $allowedExpressionType) {
            $check = 'is'.$allowedExpressionType;
            if ($allowedExpressionType == 'Reference' || $allowedExpressionType == 'RegisteredVariable') {
                if ($this->grammar->$check($argument, $this->variables)) {
                    return $allowedExpressionType;
                }
            }

            if ($this->grammar->$check($argument)) {
                return $allowedExpressionType;
            }
        }

        $errorMessage = 'The argument does not match one of these expression types: '
            .implode(', ', $allowedExpressionTypes)
            .'.';

        if (isStringable($argument)) {
            $errorMessage = "This argument '$argument', does not match one of these expression types: "
                .implode(', ', $allowedExpressionTypes)
                .'.';
        }

        throw new ExpressionTypeException($errorMessage);
    }

    /**
     * @param  array<mixed>  $argument
     * @param  array<string>|string|null  $allowedExpressionTypes
     *
     * @throws ExpressionTypeException
     * @throws BindException
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
     * @param  array<string>|string|null  $allowedExpressionTypes
     *
     * @throws ExpressionTypeException
     * @throws BindException
     */
    protected function normalizeObject(
        object $argument,
        array|string $allowedExpressionTypes = null
    ): Expression {
        if ($argument instanceof DateTimeInterface) {
            return new StringExpression($argument->format(DateTimeInterface::ATOM));
        }

        if ($argument instanceof QueryBuilder) {
            return new QueryExpression($argument);
        }

        return new ObjectExpression($this->normalizeIterable((array) $argument, $allowedExpressionTypes));
    }

    public function processBindExpression(Expression $argument): Expression
    {
        if ($argument instanceof BindExpression) {
            $bindKey = ltrim($argument->getBindVariable(), '@');

            if (! isset($this->binds[$bindKey])) {
                $this->binds[$bindKey] = $argument->getData();
            }
        }

        return $argument;
    }
}
