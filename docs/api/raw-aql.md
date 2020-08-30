# RAW AQL
You can use the raw and rawExpression commands to bypass limitations of this query builder and insert raw AQL.
For example when FluentAQL doesn't support a feature (yet). 

As always: you are responsible for its safety. Always bind external input.

## RAW
```
raw(string $aql, $binds = null, $collections = null)
```
Insert raw AQL clauses.

**Example 1: simple query**
```
$qb->raw('for user in users return user.name');
``` 

**Example 2: with binds**
```
$qb->raw('for user in users filter user.age >= @min-age && user.age <= @max-age  return u.name', ['min-age' => 18, 'max-age' => 65]);
``` 

**Example 3: with collections for deadlock prevention in cluster graph traversals or transactions**
```
$qb->raw('for user in users return user.name', null, ['read' => ['users']]);
``` 

## RAW expressions
```
rawExpression(string $aql, $binds = [], $collections = []));
``` 
Insert raw AQL as an expression.

**Example:**
```
$qb->filter(x.age, '==', $qb->rawExpression('5 * 4'));
``` 

