# Subqueries
You can easily create subqueries by passing one query bulder object to another as an expression.

Example:
```
        $subQuery = (new QueryBuilder())
            ->for('u', 'users')
            ->filter('u.active', '==', 'true')
            ->return('u._key')
            ->get();

        $result = (new QueryBuilder())
            ->for('u', 'users')
            ->filter('u._key', '==', $subQuery)
            ->return('u')
            ->get();
```

The resulting AQL is:
```
FOR u IN users FILTER u._key == (FOR u IN users FILTER u.active == true RETURN u._key) RETURN u
```

