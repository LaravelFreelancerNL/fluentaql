# Using predicates
Predicates are conditional expressions that return a boolean on evaluation.
Commonly written as `$leftOperand $comparisonOperator $rightOperand` for example: `true !== false`

Predicates are used by the following clauses: filter, prune and search;
as well as functions such as: analyzer and boost.

## Singular predicates
You can use the following syntax' to build predicates:

### Basic:
`$leftOperand, $comparisonOperator, $rightOperand`

**Example: find active users**
```php
$qb->for('u', 'users')->filter('u.active', '==', true)->return('u'); 
```
resolves to :
```aql
FOR u IN users FILTER u.active == true RETURN u 
```

### without the rightOperand
When you exclude the rightOperand a null value will be assumed.
`$leftOperand, $comparisonOperator` resolves to `$leftOperand $comparisonOperator null`

**Example: find users that don't have their age set.**
```php
$qb->for('u', 'users')->filter('u.age', '==')->return('u'); 
```
resolves to :
```aql
FOR u IN users FILTER u.active == null RETURN u 
``` 

### leftOperand only
When just supplying the leftOperand the operator and rightOperand will be left blank.
This useful to resolve the result of a function or subquery.

Note that other values such as arrays or scalars always resolve to tru
**Example: find users that have their age set.**
```php
$qb->for('u', 'users')->filter('u.age')->return('u'); 
```
resolves to :
```aql
FOR u IN users FILTER u.active RETURN u 
``` 

## Predicate groups
You can combine multiple predicates by grouping them in arrays.
**Example: get users whose age is between 17 and 68.**
```php
$qb->for('u', 'users')
    ->filter([
        ['u.age', '>=', 18],
        ['u.age', '<=', 67]
    ])
    ->return('u');
```
resolves to :
```aql
FOR u IN users FILTER u.age >= 18 AND u.age <= 67 RETURN u 
``` 
**Note:** predicate group detect is quite simplistic. A leftOperand that is an array
is assumed to be a predicate instead. If you need to use an array in the leftOperand positon use a PredicateExpression
```php
use LaravelFreelancerNL\FluentAQL\Expressions\PredicateExpression;

// ...

$qb->FILTER([
        new PredicateExpression([1, 3, 4], 'ANY IN', [ 4, 5, 6 ]),
        [false, '!==', true]
    ]);
```
resolves to :
```aql
FILTER [1, 3, 4] ANY IN [ 4, 5, 6 ] AND false !== true  
``` 

### Changing the logical operator between groups:
You can specify a logicalOperator for a predicate which will define its relation
to the preceding predicate.

In the previous example the predicates where combined with an 'AND' logical operator.
By adding an 'OR' operator to the second predicate you change this default.

**Example: get users whose age is not between 17 and 68.**
```php
$qb->for('u', 'users')
    ->filter([
        ['u.age', '<=', 18],
        ['u.age', '>=', 67, 'OR']
    ])
    ->return('u');
```
resolves to :
```aql
FOR u IN users FILTER u.age <= 18 OR u.age >= 67 RETURN u 
``` 
