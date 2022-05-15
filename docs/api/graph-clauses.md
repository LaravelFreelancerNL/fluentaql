# Graph clauses
Graph clauses allow you to traverse your database 'many to many' document-collection relationships 
along edge-collections.

[More information on the graph database model](https://www.arangodb.com/docs/stable/graphs.html). 

With the exception of the 'WITH' clause, graph traversals are mostly an extension of the 'FOR' clause.
[AQL graph traversal documentation](https://www.arangodb.com/docs/stable/aql/graphs.html)

 ## WITH
 ```
with(...$collections)
 ```
Read-lock collections for traversals. This is required for graph traversals in a cluster environment.

 **Example:**
 ```
    $qb = new QueryBuilder();
    $qb->with('users', 'managers')
 ``` 
Resulting AQL: `WITH users, managers`
 
[ArangoDB WITH documentation](https://www.arangodb.com/docs/stable/aql/operations-with.html)
 
## FOR
```
    for($variableName, $in)
```
The basic 'for' usage is described here. In graph traversals the $variableName will give you the vertexes (documents).
You can also retrieve the edges and paths by inserting an array:
```
    for([$vertex, $edge, $path])
```

$in is used to declare a min/man range of traversals.

**Example 1: vertices only**
 ```
    $qb = new QueryBuilder();
    $qb->with('users', 'managers')
        ->for('v')
 ``` 
Resulting AQL: `WITH users, managers FOR v`

**Example 2: vertices, edges and paths**
```
    $qb = new QueryBuilder();
    $qb->with('users', 'managers')
        ->for(['v', 'e', 'p'])
``` 
Resulting AQL: `WITH users, managers FOR v, e, p`

**Example 3: min/max depth of the traversal**
```
    $qb = new QueryBuilder();
    $qb->with('users', 'managers')
        ->for(['v', 'e', 'p'], '1..3')
``` 
Resulting AQL: `WITH users, managers FOR v, e, p IN 1..3`


## TRAVERSE
```
    traverse($fromVertex, $inDirection = 'outbound', $toVertex = null)
```
The traverse clause follows 'FOR'. With this clause you declare the starting point, direction and optional endpoint of 
the traversal.

**Example 1: retrieve all related cities**
```
    $qb = new QueryBuilder();
    $qb->with('users', 'cities')
        ->for(['v', 'e', 'p'])
        ->traverse('users/1', 'ANY')
``` 
Resulting AQL: `WITH users, cities FOR v, e, p ANY "users/1"`

**Example 2: retrieve all related data for all paths from a city to a user**
```
    $qb = new QueryBuilder();
    $qb->with('users', 'cities')
        ->for(['v', 'e', 'p'])
        ->traverse('users/1', 'INBOUND', 'cities/10')
``` 
Resulting AQL: `WITH users, cities FOR v, e, p INBOUND "users/1" "cities/10"`

## SHORTEST-PATH
```
   shortestPath($fromVertex, $inDirection, $toVertex)
```
ShortestPath is a replacement for traverse and gives you the shortest path fom A to B in the chosen direction.

**Example 1: retrieve all related cities**
```
    $qb = new QueryBuilder();
    $qb->with('users', 'cities')
        ->for(['v', 'e', 'p'])
        ->shortestPath('users/1', 'ANY')
``` 

**Example 2: retrieve all related data for all paths from a city to a user**
```
    $qb = new QueryBuilder();
    $qb->with('users', 'cities')
        ->for(['v', 'e', 'p'])
        ->shortestPath('users/1', 'INBOUND', 'cities/10')
``` 


## K-SHORTEST-PATHS
```
    kShortestPaths($fromVertex, $inDirection, $toVertex)
```
kShortestPaths is a replacement for traverse and gives you all the paths between two points ordered by ascending weight.
The weight is set through the 'OPTIONS' clause.

**Example 1: retrieve all related cities**
```
    $qb = new QueryBuilder();
    $qb->with('users', 'cities')
        ->for(['v', 'e', 'p'])
        ->shortestPath('users/1', 'ANY')
``` 

**Example 2: retrieve all related data for all paths from a city to a user**
```
    $qb = new QueryBuilder();
    $qb->with('users', 'cities')
        ->for(['v', 'e', 'p'])
        ->shortestPath('users/1', 'INBOUND', 'cities/10')
``` 

## GRAPH (named graph)
```
    graph(string $graphName)
```
Execute the previously set traversal on a named graph.

**Example:**
```
    $qb = new QueryBuilder();
    $qb->with('users', 'cities')
        ->for(['v', 'e', 'p'])
        ->traverse('users/1', 'ANY')
        ->graph("citizens")
``` 
Resulting AQL: `WITH users, cities FOR v, e, p ANY "users/1" GRAPH "citizens"`

[ArangoDB NAMED GRAPH documentation](https://www.arangodb.com/docs/stable/aql/graphs-traversals.html#working-with-named-graphs)

## EDGE COLLECTIONS (unnamed graph)
```
edgeCollections(...$edgeCollections)
```
Execute the previously set traversal on the given set of edge collections.
To specify a direction add it before the accompanying edge collection. 

**Example 1: multiple edge collections**
```
    $qb = new QueryBuilder();
    $qb->with('users', 'cities')
        ->for(['v', 'e', 'p'])
        ->traverse('users/1', 'ANY')
        ->edgeCollections('edge1', 'edge2', 'edge3')
``` 
Resulting AQL: `WITH users, cities FOR v, e, p ANY "users/1" edge1, edge2, edge3`

**Example 2: with directions**
```
    $qb = new QueryBuilder();
    $qb->with('users', 'cities')
        ->for(['v', 'e', 'p'])
        ->traverse('users/1', 'ANY')
        ->edgeCollections('edge1', 'inbound', edge2', 'edge3')
``` 
Resulting AQL: `WITH users, cities FOR v, e, p ANY "users/1" edge1, INBOUND edge2, edge3`


[ArangoDB UNNAMED GRAPH documentation](https://www.arangodb.com/docs/stable/aql/graphs-traversals.html#working-with-collection-sets)


## PRUNE
```
prune($leftOperand, $comparisonOperator = null, $rightOperand = null, $logicalOperator = null, $pruneVariable)
```
Filter out data not matching the given predicate(s).

**Example - single predicate:**
```
    $qb = (new QueryBuilder());
    $qb->for(['v', 'e', 'p'], '1..5')
        ->traverse('circles/A', 'OUTBOUND')
        ->graph("traversalGraph")
        ->prune('e.theTruth', '==', true)
        ->return([
            'vertices' => 'p.vertices[*]._key',
            'edges' => 'p.edges[*].label'
        ])
        ->get();
``` 
Resulting AQL: 
```
FOR v, e, p IN 1..5 OUTBOUND "circles/A" GRAPH "traversalGraph"'
    PRUNE e.theTruth == true
    RETURN {"vertices":p.vertices[*]._key,"edges":p.edges[*].label}
```

**Example - multiple predicates:**
```
    $qb = (new QueryBuilder());
    $qb->for(['v', 'e', 'p'], '1..5')
        ->traverse('circles/A', 'OUTBOUND')
        ->graph("traversalGraph")
        ->prune([['e.active', '==', 'true'], ['e.age', '>', 18]])
        ->return([
            'vertices' => 'p.vertices[*]._key',
            'edges' => 'p.edges[*].label'
        ])
        ->get();
``` 
Resulting AQL:
```
FOR v, e, p IN 1..5 OUTBOUND "circles/A" GRAPH "traversalGraph"
    PRUNE e.active == true AND e.age > 18
    RETURN {"vertices":p.vertices[*]._key,"edges":p.edges[*].label}
```

**Example - store condition in variable:**
```
    $qb = (new QueryBuilder());
    $qb->for(['v', 'e', 'p'], '1..5')
        ->traverse('circles/A', 'OUTBOUND')
        ->graph("traversalGraph")
        ->prune(
            'e.theTruth',
            '==',
            true,
            'OR',
            'pruneCondition'
        )
        ->return([
            'vertices' => 'p.vertices[*]._key',
            'edges' => 'p.edges[*].label'
        ])
        ->get();
``` 
Resulting AQL:
```
    FOR v, e, p IN 1..5 OUTBOUND "circles/A" GRAPH "traversalGraph"
        PRUNE pruneCondition = e.theTruth == true
        RETURN {"vertices":p.vertices[*]._key,"edges":p.edges[*].label}
```


**Example - multiple predicates stored in variable:**
```
    $qb = (new QueryBuilder());
    $qb->for(['v', 'e', 'p'], '1..5')
        ->traverse('circles/A', 'OUTBOUND')
        ->graph("traversalGraph")
        ->prune(
            [
                'e.theTruth',
                '==',
                true
            ],
            pruneVariable: 'pruneCondition'
        )
        ->return([
            'vertices' => 'p.vertices[*]._key',
            'edges' => 'p.edges[*].label'
        ])
        ->get();
``` 
Resulting AQL:
```
    FOR v, e, p IN 1..5 OUTBOUND "circles/A" GRAPH "traversalGraph"
        PRUNE pruneCondition = e.theTruth == true
        RETURN {"vertices":p.vertices[*]._key,"edges":p.edges[*].label}
```

[ArangoDB PRUNE documentation](https://www.arangodb.com/docs/stable/aql/graphs-traversals.html#using-filters-and-the-explainer-to-extrapolate-the-costs)
