# Data binding
```
bind($data, $to = null)
```
Inject data through the bind command. 

**Example 1:**
```
$qb->filter('u.age', '==', $qb->bind(18));
```
Resulting AQL: `FILTER u.age == @{a_generated_id}`

**Example 2 - with bind id:**
```
$qb->filter('u.age', '==', $qb->bind(18, 'my_bind'));
``` 
Resulting AQL: `FILTER u.age == @my_bind`

## Bind collection names
```
bindCollection($data, $to = null)
```
Inject collection names through the bindCollection command. 

**Example 1:**
```
$qb->for('user', $qb->bindCollection('users'));
```
Resulting AQL: `FOR user IN @@{a_generated_id}`

**Example 2 - with bind id:**
```
$qb->for('user', $qb->bindCollection('users', 'collectionBind'));
``` 
Resulting AQL: `FOR user IN @@collectionBind`

