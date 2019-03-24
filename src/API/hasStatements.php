<?php
namespace LaravelFreelancerNL\FluentAQL\API;

use LaravelFreelancerNL\FluentAQL\Clauses\FilterStatement;
use LaravelFreelancerNL\FluentAQL\Clauses\ForStatement;
use LaravelFreelancerNL\FluentAQL\Clauses\ReturnStatement;

/**
 * Trait hasStatements
 * API calls to add statement commands to the builder.
 *
 * @package LaravelFreelancerNL\FluentAQL\API
 */
trait hasStatements
{

    public function for($variableName, $in)
    {
        $this->addCommand(new ForStatement($variableName, $in));

        return $this;
    }

    public function return($expression)
    {
        $this->addCommand(new ReturnStatement($expression));

        return $this;
    }

    public function filter($column, $operator, $value, $boolean)
    {
        $this->addCommand(new FilterStatement($column, $operator, $value, $boolean));

        return $this;
    }
}