<?php
namespace LaravelFreelancerNL\FluentAQL\Functions;

class DocumentFunction extends AqlFunction
{

    protected $collection;

    protected $id;

    function __construct($collection, $id = null)
    {
        if ($id === null) {
            $id = $collection;
            $collection = null;
        }

        $this->collection = $collection;

        $this->id= $id;
    }

    public function compile()
    {
        $parameters = [];

        if ($this->collection !== null) {
            $parameters[] = $this->collection;
        }

        $id = json_encode($this->id, JSON_UNESCAPED_SLASHES );
        $parameters[] = $id;

        $query = 'DOCUMENT('.implode(', ', $parameters).')';

        $bindings = [];

        $collections = ['read' => [$this->collection]];

        return [
            'query' => $query,
            'bindings' => $bindings,
            'collections' => $collections
        ];
    }
}