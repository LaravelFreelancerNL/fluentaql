# Operator expressions
AQL has two special operator expressions that require their own functions:
- Ternary expressions (inline if/else)
- Arithmetic expressions (calculations)

## if()
```
if($conditions, $then, $else)
```

**Example 1: basic if expression**
```
        $qb = new QueryBuilder();
        $qb->let('x', 5)->return($qb->if(['x', '==', '5'], true, false));
``` 
Resulting AQL: `LET x = 5 RETURN (x == 5) ? true : false`

[ArangoDB Ternary Operator documentation](https://www.arangodb.com/docs/stable/aql/operators.html#ternary-operator)

## calc()
```
calc($leftOperand, $operator, $rightOperand)
```
The left and right operands can be numbers or should result in a number.
Number strings are considered to be numbers. So the string '5' equals the number 5.

**Example 1: basic calculation**
```
    $qb = new QueryBuilder();
    $qb->return($qb->calc(3, '*', 3));
``` 
Resulting AQL: `RETURN 3 * 3`


**Example 2: calculation with another calculation embedded**
```
    $qb = new QueryBuilder();
    $qb->return($qb->calc(3, '+', $qb->calc(3, '*', 3)));
``` 
Resulting AQL: `RETURN 3 + (3 * 3)`

[ArangoDB Arithmetic Operator documentation](https://www.arangodb.com/docs/stable/aql/operators.html#arithmetic-operators)
