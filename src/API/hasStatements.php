<?php
namespace LaravelFreelancerNL\FluentAQL\API;

use LaravelFreelancerNL\FluentAQL\Statements\ForStatement;
use LaravelFreelancerNL\FluentAQL\Statements\ReturnStatement;

/**
 * Trait hasStatements
 * API calls to add statement commands to the builder.
 *
 * @package LaravelFreelancerNL\FluentAQL\API
 */
trait hasStatements
{

    public function for($vertexVariableName, $edgeVariableName = null, $pathVariableName = null)
    {
        $this->addCommand(new ForStatement($vertexVariableName, $edgeVariableName, $pathVariableName));

        return $this;
    }

    public function return($expression)
    {
        $this->addCommand(new ReturnStatement($expression));

        return $this;
    }

}