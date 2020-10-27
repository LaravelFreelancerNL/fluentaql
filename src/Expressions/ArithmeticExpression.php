<?php

namespace LaravelFreelancerNL\FluentAQL\Expressions;

use LaravelFreelancerNL\FluentAQL\Exceptions\ExpressionTypeException;
use LaravelFreelancerNL\FluentAQL\QueryBuilder;

class ArithmeticExpression extends PredicateExpression implements ExpressionInterface
{

    protected $calculation = [];

    /**
     * Create predicate expression.
     *
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $operator
     */
    public function __construct($leftOperand, $operator, $rightOperand)
    {
        $this->calculation = [$leftOperand, $operator, $rightOperand];
    }

    /**
     * Compile calculation.
     *
     * @param  QueryBuilder|null  $queryBuilder
     * @return string
     * @throws \Exception
     */
    public function compile(QueryBuilder $queryBuilder = null): string
    {
        $normalizedCalculation = $this->normalizeCalculation($queryBuilder, $this->calculation);

        $leftOperand = $normalizedCalculation['leftOperand']->compile($queryBuilder);
        if ($normalizedCalculation['leftOperand'] instanceof ArithmeticExpression) {
            $leftOperand = '(' . $leftOperand . ')';
        }

        $rightOperand = $normalizedCalculation['rightOperand']->compile($queryBuilder);
        if ($normalizedCalculation['rightOperand'] instanceof ArithmeticExpression) {
            $rightOperand = '(' . $rightOperand . ')';
        }

        return $leftOperand . ' ' . $normalizedCalculation['arithmeticOperator'] . ' ' . $rightOperand;
        return $leftOperand . ' ' . $normalizedCalculation['arithmeticOperator'] . ' ' . $rightOperand;
    }

    /**
     * @param  QueryBuilder  $queryBuilder
     * @param  array  $calculation
     * @return mixed
     * @throws ExpressionTypeException
     */
    public function normalizeCalculation(QueryBuilder $queryBuilder, array $calculation)
    {
        $normalizedCalculation = [];

        $leftOperand = $queryBuilder->normalizeArgument($calculation[0]);

        $arithmeticOperator = '+';
        if ($queryBuilder->grammar->isArithmeticOperator($calculation[1])) {
            $arithmeticOperator = $calculation[1];
        }

        $rightOperand = $queryBuilder->normalizeArgument($calculation[2]);

        $normalizedCalculation['leftOperand'] = $leftOperand;
        $normalizedCalculation['arithmeticOperator'] = $arithmeticOperator;
        $normalizedCalculation['rightOperand'] = $rightOperand;

        return $normalizedCalculation;
    }


}
