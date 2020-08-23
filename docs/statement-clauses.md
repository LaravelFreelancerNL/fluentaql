# Statement clauses
Statement clauses are AQL clauses for manipulating data and declaring variables.

(The clauses below are ordered in a logical CrUD order to help new users of this package)

## LET
```
let($variableName, $expression)
```
Assign the expression to the variable.

**Example 1 - array:**
```
$qb->let('i', [1,2,3,4]);
``` 
Resulting AQL: `LET [1,2,3,4]`

**Example 2 - subquery:**
```

$subquery = new QueryBuilder();
$subquery = $subquery->for('u', users')->filter('u.name', '==', 'Stark')->limit(1)->return('u._id')

$qb->let('familyMembers', $subquery);
``` 
Resulting AQL: `LET familyMembers = (FOR u IN users FILTER u.name == "Stark" LIMIT 1 RETURN u.id)`

[ArangoDB LET documentation](https://www.arangodb.com/docs/stable/aql/operations-let.html)

## INSERT
```
insert($document, string $collection)
```
Insert a document into a collection.

**Example 1: singular insert**
```
$qb->insert(['value' => 1], 'numbers');
``` 
Resulting AQL: `INSERT {"value": 1} INTO numbers`

**Example 2: multiple insert**
```
$qb->for('i', '1..100')->insert(['value' => 'i'], 'numbers');
``` 
Resulting AQL: `FOR i IN 1..100 INSERT {"value": i} INTO numbers`

[ArangoDB INSERT documentation](https://www.arangodb.com/docs/stable/aql/operations-insert.html)


## UPDATE
```
update($document, $with, $collection)
```
(Partially) update a document in a collection.

**Example:**
```
$qb->insert(['_key' => 2343241], ['age' => 19], 'users');
``` 
Resulting AQL: `UPDATE {"_key": 2343241} WITH {"age": 19} IN users`

[ArangoDB UPDATE documentation](https://www.arangodb.com/docs/stable/aql/operations-update.html)


## REPLACE
```
replace($document, $with, string $collection)
```
(Partially) update a document in a collection.

**Example:**
```
$qb->insert(['_key' => 2343241], ['age' => 19], 'users');
``` 
Resulting AQL: `REPLACE {"_key": 2343241} WITH {"age": 19} IN users`

[ArangoDB REPLACE documentation](https://www.arangodb.com/docs/stable/aql/operations-update.html)


## UPSERT
```
upsert($search, $insert, $with, string $collection, bool $replace = false)
```
Update/replace a document if it exists, insert it if it doesn't.

**Example: with raw json**
```
$qb->upsert(
        '{ name: "superuser" }',
        '{ name: "superuser", logins: 1, dateCreated: DATE_NOW() }',
        '{ logins: OLD.logins + 1 }',
        'users'
    );
``` 
Resulting AQL: `REPLACE {"_key": 2343241} WITH {"age": 19} IN users`

[ArangoDB REPLACE documentation](https://www.arangodb.com/docs/stable/aql/operations-upsert.html)


 ## REMOVE
 ```
 remove($document, string $collection)
 ```
Remove a document from a collection
 
 **Example:**
 ```
    ->remove('user', 'users')
 ``` 
 Resulting AQL: `REMOVE user IN users`
 
 [ArangoDB REMOVE documentation](https://www.arangodb.com/docs/stable/aql/operations-remove.html)
