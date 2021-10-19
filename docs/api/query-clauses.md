# Query clauses
Query clauses are AQL clauses for selecting, filtering and/or retrieving data.

## FOR
```
for($variableName, $in = null)
```
Iterate through a data set ($in) and provide the current value to the rest of the query through the $variableName.

**Example 1 - collection:**
```php
$qb->for('user', 'users');
``` 
Resulting AQL: `FOR user IN users`

**Example 2 - range:**
```php
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
```php
$qb->for('user', 'users)
    ->return('user');
``` 
Resulting AQL: `... RETURN user`

[ArangoDB RETURN documentation](https://www.arangodb.com/docs/stable/aql/operations-return.html)

## FILTER
```
filter($leftOperand, $comparisonOperator = null, $rightOperand = null, $logicalOperator = null)
```
Filter out data not matching the given predicate(s). You may add a single predicate or a list of predicates.
Predicates can be embedded up to one level deep.

**Example 1 - single predicate:**
```php
$qb->for('user', 'users')
    ->filter('user.age', '==', 18)
    ->return('user);
``` 
Resulting AQL: `... FILTER u.age == 18 ...`

**Example 2 - multiple predicates:**
```php
$qb->for('user', 'users')
    ->filter(['u.age', '>=', 18], ['u.age', '<=', 65]))
    ->return('user);
``` 
Resulting AQL: `... FILTER u.age >= 18 AND u.age <= 65 ...`

**Example 3 - multiple predicates, logical OR:**
```php
$qb->for('user', 'users')
    ->filter(['u.age', '<', 18], ['u.age', '>', 65, 'OR']))
    ->return('user);
``` 
Resulting AQL: `... FILTER u.age < 18 OR u.age > 65 ...`

**Example 4 - embedded predicates:**
```php
$filter = [
    [
        ['doc.attribute1', '!=', null, 'AND'],
        ['doc.attribute2', '!=', 'null', 'OR']
    ],
    ['doc.attribute3', '==', 'null', 'OR']
];
(new QueryBuilder())
    ->for('doc', 'documents')
    ->filter($filter)
    ->get();

``` 
Resulting AQL: `... FILTER (doc.attribute1 != null OR doc.attribute2 != null) OR doc.attribute3 == null ...`



[ArangoDB FILTER documentation](https://www.arangodb.com/docs/stable/aql/operations-filter.html)

## SEARCH
```
search($leftOperand, $comparisonOperator = null, $rightOperand = null, $logicalOperator = null)
```
Filters equivalent specifically for data from *ArangoSearch Views*

**Example 1 - single predicate:**
```php
$qb->for('user', 'users')
    ->search('u.age', '==', 18)
    ->return('user);
``` 
Resulting AQL: `... SEARCH u.age == 18 ...`

**Example 2 - multiple predicates:**
```php
$qb->for('user', 'users')
    ->search(['u.age', '>=', 18], ['u.age', '<=', 65]))
    ->return('user);
``` 
Resulting AQL: `...  SEARCH u.age >= 18 AND u.age <= 65 ...`

**Example 3 - multiple predicates, logical OR:**
```php
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
Return the result of a (sub)query. To set a direction add the appropriate string attribute to the method.

**Example 1: default direction (asc)**
```php
$qb->sort('user.name');
``` 
Resulting AQL: `...  SORT user.name ...`

**Example 2: descending direction**
```php
$qb->sort('user.name', 'desc');
``` 
Resulting AQL: `...  SORT user.name DESC ...`

**Example 3: sort by multiple attributes**
```php
$qb->sort('user.name', 'desc', 'user.email');
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
```php
$qb->limit(10);
``` 
Resulting AQL: `... LIMIT 10 ...`

**Example 2: limit the results to 10 starting at 5**
```php
$qb->limit(5, 10);
``` 
Resulting AQL: `... LIMIT 5 10 ...`

[ArangoDB LIMIT documentation](https://www.arangodb.com/docs/stable/aql/operations-limit.html)

## COLLECT
```
collect($variableName = null, $expression = null)
```
Group an array by one or more into criteria.

**Example 1:**
```php
$qb->collect('cities', 'user.city');
``` 
Resulting AQL: `... COLLECT cities = user.city ...`

**Example 2: limit the results to 10 starting at 5**
```php
$qb->collect('cities', 'user.city', 'surnames', 'user.surname');
``` 
Resulting AQL: `... COLLECT cities = user.city, surnames = user.surname ...``

[ArangoDB COLLECT documentation](https://www.arangodb.com/docs/stable/aql/operations-collect.html)

### INTO
```
into($groupsVariable, $projectionExpression = null)
```
This addition to collect stores all collected elements in $groupsVariable.

**Note:** the into clause is only useful following a collect or aggregate clause.

**Example 1: group the users that are in the collected city**
```php
$qb->for('user', 'users')
    ->let('name', 'user.name')
    ->collect('cities', 'user.city')
        ->into('users-in-this-city')
    ->return(['city' => 'city', 'usersInThisCity' => 'users-in-this-city']);
``` 
Resulting AQL: `... COLLECT cities = user.city INTO users-in-this-city ...`


**Example 2: group the users that are in the collected city. By name only.**
```php
$qb->for('user', 'users')
    ->let('name', 'user.name')
    ->collect('cities', 'user.city')
        ->into('users-in-this-city', 'user.name')
    ->return(['city' => 'city', 'usersInThisCity' => 'users-in-this-city']);
``` 
Resulting AQL: `... COLLECT cities = user.city INTO users-in-this-city = user.name ...`

[ArangoDB 'GROUP' documentation](https://www.arangodb.com/docs/stable/aql/operations-collect.html#grouping-syntaxes)


### KEEP
```
keep($keepVariable)
```
Discard anything but the attributes to keep from the grouped data.    

**Note 1:** the keep clause is only useful following the into clause.

**Example 1: into the users that are in the collected city. Only keep the name variable**
```php
$qb->for('user', 'users')
    ->let('name', 'user.name')
    ->let('something', 'else')
    ->collect('cities', 'user.city')
        ->into('users-in-this-city')
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

**Note:** the with count clause is only be used with into.

**Example: **
```php
$qb->for('user', 'users')
    ->let('name', 'user.name')
    ->let('something', 'else')
    ->collect('cities', 'user.city')
        ->into('users-in-this-city')
        ->keep('name')
    ->return(['city' => 'city', 'usersInThisCity' => 'users-in-this-city']);
``` 

[ArangoDB KEEP documentation](https://www.arangodb.com/docs/stable/aql/operations-collect.html#discarding-obsolete-variables)

### AGGREGATE
```
aggregate($variableName, $aggregateExpression)
```
Aggregate collected data per into

**Note:** the aggregate clause can only be used after the collect clause.

**Example: **
```php
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

## WINDOW
```
window(
        array|object $offsets,
        string|QueryBuilder|Expression $rangeValue = null
    )
```
Aggregate adjacent documents or value ranges with a sliding window
to calculate running totals, rolling averages, and other statistical properties

**Example 1:**
```php
$qb->window(['preceding' => 5, 'following' => 10])
``` 
Resulting AQL: `... WINDOW { "preceding": 5, "following": 10 } ...`

**Example 2: using range-based aggregation**
```php
$qb->window(['preceding' => 5, 'following' => 10], 't.time')
``` 
Resulting AQL: `... WINDOW t.time WITH { "preceding": 5, "following": 10 } ...`

[ArangoDB WINDOW documentation](https://www.arangodb.com/docs/stable/aql/operations-window.html)


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
```php
$qb->for('user', 'users')
    ->options([indexHint => 'byName', 'forceIndexHint' => true])
    ->return('user');
``` 
Resulting AQL: `... OPTIONS {indexHint: 'byName', forceIndexHint: true} ...`

