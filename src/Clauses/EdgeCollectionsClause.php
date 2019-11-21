<?php

namespace LaravelFreelancerNL\FluentAQL\Clauses;

class EdgeCollectionsClause extends Clause
{
    protected $edgeCollections;

    public function __construct(array $edgeCollections)
    {
        parent::__construct();

        $this->edgeCollections = $edgeCollections;
    }

    public function compile()
    {
        return implode(', ', array_map(function($expression) {
            $output = '';
            if (isset($expression[1])) {
                $output = $expression[1].' ';
            }

            return $output.$expression[0];
        }, $this->edgeCollections));
    }
}
