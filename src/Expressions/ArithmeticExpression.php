<?php

declare(strict_types=1);

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\Exceptions\ExpressionTypeException;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class ArithmeticExpression extends Expression implements ExpressionInterface
{
    /**
     * @var array<int|string|float|null|Expression|QueryBuilder>
     */
    protected array $calculation = [];

    /**
     * Create predicate expression.
     *
     * @param  int|float|null|Expression|QueryBuilder  $leftOperand
     * @param  string  $operator
     * @param  int|float|null|Expression|QueryBuilder  $rightOperand
     */
    public function __construct(
        int|float|null|Expression|QueryBuilder $leftOperand,
        string $operator,
        int|float|null|Expression|QueryBuilder $rightOperand
    ) {
        $this->calculation = [$leftOperand, $operator, $rightOperand];
    }

    /**
     * Compile calculation.
     *
     * @param  QueryBuilder  $queryBuilder
     * @return string
     *
     * @throws \Exception
     */
    public function compile(QueryBuilder $queryBuilder): string
    {
        /** @var Expression[] $normalizedCalculation */
        $normalizedCalculation = $this->normalizeCalculation($queryBuilder, $this->calculation);

        $leftOperand = $normalizedCalculation['leftOperand']->compile($queryBuilder);
        if ($normalizedCalculation['leftOperand'] instanceof self) {
            $leftOperand = '('.$leftOperand.')';
        }

        $arithmeticOperator = $normalizedCalculation['arithmeticOperator']->compile($queryBuilder);

        $rightOperand = $normalizedCalculation['rightOperand']->compile($queryBuilder);
        if ($normalizedCalculation['rightOperand'] instanceof self) {
            $rightOperand = '('.$rightOperand.')';
        }

        return $leftOperand.' '.$arithmeticOperator.' '.$rightOperand;
    }

    /**
     * @param  QueryBuilder  $queryBuilder
     * @param  array<int|float|string|null|Expression|QueryBuilder>  $calculation
     * @return array<mixed>
     *
     * @throws ExpressionTypeException
     */
    public function normalizeCalculation(
        QueryBuilder $queryBuilder,
        array $calculation
    ): array {
        $normalizedCalculation = [];

        $leftOperand = $queryBuilder->normalizeArgument($calculation[0]);

        $arithmeticOperator = '+';
        /** @var string $calculation[1] */
        if ($queryBuilder->grammar->isArithmeticOperator($calculation[1])) {
            $arithmeticOperator = new LiteralExpression($calculation[1]);
        }

        $rightOperand = $queryBuilder->normalizeArgument($calculation[2]);

        $normalizedCalculation['leftOperand'] = $leftOperand;
        $normalizedCalculation['arithmeticOperator'] = $arithmeticOperator;
        $normalizedCalculation['rightOperand'] = $rightOperand;

        return $normalizedCalculation;
    }
}
