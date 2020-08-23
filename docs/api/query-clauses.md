# Query clauses
Query clauses are AQL clauses for selecting, filtering and/or retrieving data.

## FOR
```
for($variableName, $in = null)
```
Iterate through a data set ($in) and provide the current value to the rest of the query through the $variableName.

**Example 1 - collection:**
```
$qb->for('user', 'users');
``` 
Resulting AQL: `FOR user IN users`

**Example 2 - range:**
```
$qb->for('i', '1..100');
``` 
Resulting AQL: `FOR i IN 1..100`

[ArangoDB FOR documentation](https://www.arangodb.com/docs/stable/aql/operations-for.html)

## RETURN
```
return($expression, $distinct = false)
```
Return the result of a (sub)query.

**Example:**
```
$qb->for('user', 'users)
    ->return('user');
``` 
Resulting AQL: `... RETURN user`

[ArangoDB RETURN documentation](https://www.arangodb.com/docs/stable/aql/operations-return.html)

## FILTER
```
filter($leftOperand, $comparisonOperator = null, $rightOperand = null, $logicalOperator = null)
```
Filter out data not matching the given predicate(s).

**Example 1 - single predicate:**
```
$qb->for('user', 'users')
    ->filter('user.age', '==', 18)
    ->return('user);
``` 
Resulting AQL: `... FILTER u.age == 18 ...`

**Example 2 - multiple predicates:**
```
$qb->for('user', 'users')
    ->filter(['u.age', '>=', 18], ['u.age', '<=', 65]))
    ->return('user);
``` 
Resulting AQL: `... FILTER u.age >= 18 AND u.age <= 65 ...`

**Example 3 - multiple predicates, logical OR:**
```
$qb->for('user', 'users')
    ->filter(['u.age', '<', 18], ['u.age', '>', 65, 'OR']))
    ->return('user);
``` 
Resulting AQL: `... FILTER u.age < 18 OR u.age > 65 ...`

[ArangoDB FILTER documentation](https://www.arangodb.com/docs/stable/aql/operations-filter.html)

## SEARCH
```
search($leftOperand, $comparisonOperator = null, $rightOperand = null, $logicalOperator = null)
```
Filters equivalent specifically for data from *ArangoSearch Views*

**Example 1 - single predicate:**
```
$qb->for('user', 'users')
    ->search('u.age', '==', 18)
    ->return('user);
``` 
Resulting AQL: `... SEARCH u.age == 18 ...`

**Example 2 - multiple predicates:**
```
$qb->for('user', 'users')
    ->search(['u.age', '>=', 18], ['u.age', '<=', 65]))
    ->return('user);
``` 
Resulting AQL: `...  SEARCH u.age >= 18 AND u.age <= 65 ...`

**Example 3 - multiple predicates, logical OR:**
```
$qb->for('user', 'users')
    ->search(['u.age', '<', 18], ['u.age', '>', 65, 'OR']))
    ->return('user);
``` 
Resulting AQL: `...  SEARCH u.age < 18 OR u.age > 65 ...`

[ArangoDB SEARCH documentation](https://www.arangodb.com/docs/stable/aql/operations-search.html)

## SORT
```
sort($reference, $direction)
```
Return the result of a (sub)query.

**Example 1: default direction (asc)**
```
$qb->sort('user.name');
``` 
Resulting AQL: `...  SORT user.name ...`

**Example 2: descending direction**
```
$qb->sort('user.name', 'desc');
``` 
Resulting AQL: `...  SORT user.name DESC ...`

**Example 3: sort by multiple attributes**
```
$qb->sort(['user.name', 'desc'], 'user.email');
``` 
Resulting AQL: `...  SORT user.name DESC, user.email ...`

[ArangoDB SORT documentation](https://www.arangodb.com/docs/stable/aql/operations-sort.html)


## LIMIT
```
limit(int $offsetOrCount, int $count = null)
```
Limit the number of results.

**Note:** when supplying just one parameter that is the *limit count*.
When supplying two parameters the first one is the *offset*.

**Example 1: limit the results to 10**
```
$qb->limit(10);
``` 
Resulting AQL: `... LIMIT 10 ...`

**Example 2: limit the results to 10 starting at 5**
```
$qb->limit(5, 10);
``` 
Resulting AQL: `... LIMIT 5 10 ...`

[ArangoDB LIMIT documentation](https://www.arangodb.com/docs/stable/aql/operations-limit.html)

## COLLECT
```
collect($variableName = null, $expression = null)
```
Group an array by one or more group criteria.

**Example 1:**
```
$qb->collect('cities', 'user.city');
``` 
Resulting AQL: `... COLLECT cities = user.city ...`

[ArangoDB COLLECT documentation](https://www.arangodb.com/docs/stable/aql/operations-collect.html)

### GROUP
```
group($groupsVariable, $projectionExpression = null)
```
This addition to collect stores all collected elements in $groupsVariable.

**Note 1:** the group clause is only useful following a collect or aggregate clause.
**Note 2:** AQL does not have an actual group clause. Within this query builder we use GROUP to prevent confusing
with the 'INTO' keyword that is used in other places in AQL as well.

**Example 1: group the users that are in the collected city**
```
$qb->for('user', 'users')
    ->let('name', 'user.name')
    ->collect('cities', 'user.city')
        ->group('users-in-this-city')
    ->return(['city' => 'city', 'usersInThisCity' => 'users-in-this-city']);
``` 
Resulting AQL: `... COLLECT cities = user.city INTO users-in-this-city ...`


**Example 2: group the users that are in the collected city. By name only.**
```
$qb->for('user', 'users')
    ->let('name', 'user.name')
    ->collect('cities', 'user.city')
        ->group('users-in-this-city', 'user.name')
    ->return(['city' => 'city', 'usersInThisCity' => 'users-in-this-city']);
``` 
Resulting AQL: `... COLLECT cities = user.city INTO users-in-this-city = user.name ...`

[ArangoDB 'GROUP' documentation](https://www.arangodb.com/docs/stable/aql/operations-collect.html#grouping-syntaxes)


### KEEP
```
group($groupsVariable, $projectionExpression = null)
```
Discard anything but the attributes to keep from the grouped data.    

**Note 1:** the keep clause is only useful following the group clause.

**Example 1: group the users that are in the collected city. Only keep the name variable**
```
$qb->for('user', 'users')
    ->let('name', 'user.name')
    ->let('something', 'else')
    ->collect('cities', 'user.city')
        ->group('users-in-this-city')
        ->keep('name')
    ->return(['city' => 'city', 'usersInThisCity' => 'users-in-this-city']);
``` 
Resulting AQL: `... COLLECT cities = user.city INTO users-in-this-city = user.name KEEP name ...`

[ArangoDB KEEP documentation](https://www.arangodb.com/docs/stable/aql/operations-collect.html#discarding-obsolete-variables)

### WITH COUNT
```
withCount($countVariableName)
```
Discard anything but the attributes to keep from the grouped data.    

**Note:** the with count clause is only be used with group.

**Example: **
```
$qb->for('user', 'users')
    ->let('name', 'user.name')
    ->let('something', 'else')
    ->collect('cities', 'user.city')
        ->group('users-in-this-city')
        ->keep('name')
    ->return(['city' => 'city', 'usersInThisCity' => 'users-in-this-city']);
``` 

[ArangoDB KEEP documentation](https://www.arangodb.com/docs/stable/aql/operations-collect.html#discarding-obsolete-variables)

### AGGREGATE
```
aggregate($variableName, $aggregateExpression)
```
Aggregate collected data per group

**Note:** the aggregate clause can only be used after the collect clause.

**Example: **
```
$qb->for('user', 'users')
    ->collect('ageGroup', $qb->floor($qb->raw('(user.age /5) * 5')))
        ->aggregate('minAge', $qb->min('user.age'))
    ->return(['ageGroup', 'minAge']);
```

Resulting AQL: 
``` 
FOR user IN users
  COLLECT ageGroup = FLOOR(user.age / 5) * 5 
  AGGREGATE minAge = MIN(user.age)
  RETURN {
    ageGroup, 
    minAge 
  }
``` 

[ArangoDB AGGREGATE documentation](https://www.arangodb.com/docs/stable/aql/operations-collect.html#aggregation)

## Other clauses

### OPTIONS
```
options($options)
```
`options()` accepts an associative array or iterable object containing the option settings.

Options() can be only used with the following clauses:
- for
- search
- collect
- insert
- update
- upsert
- replace
- remove
 
[Read their documentation on the ArangoDB site for specific options for each clause](https://www.arangodb.com/docs/stable/aql/operations.html).

**Note** that you can't use binds in option(). Strings remain strings and are not bound by the query builder.
So be sure not to pass in any outside data directly. 

**Example: **
```
$qb->for('user', 'users')
    ->options([indexHint => 'byName', 'forceIndexHint' => true])
    ->return('user');
``` 
Resulting AQL: `... OPTIONS {indexHint: 'byName', forceIndexHint: true} ...`

### RAW
```
raw(string $aql, $binds = null, $collections = null)
```
This clause allows you to write raw AQL. This is particularly useful for AQL features that this query builder doesn't support yet.

Of course that means you are responsible for its safety. As always bind user data.


**Example 1: simple query**
```
$qb->raw('for user in users return user.name');
``` 

**Example 2: with binds**
```
$qb->raw('for user in users filter user.age >= @min-age && user.age <= @max-age  return u.name', ['min-age' => 18, 'max-age' => 65]);
``` 

**Example 2: with collections for deadlock prevention in cluster graph traversals or transactions**
```
$qb->raw('for user in users return user.name', null, ['read' => ['users']]);
``` 

