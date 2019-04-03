<?php
namespace LaravelFreelancerNL\FluentAQL\API;

use LaravelFreelancerNL\FluentAQL\Functions\DocumentFunction;

/**
 * Trait hasFunctions
 * API calls to add AQL function commands to the builder.
 *
 * @package LaravelFreelancerNL\FluentAQL\API
 */
trait hasFunctions
{

    public function document($collection, $id = null)
    {
        $this->addCommand(new DocumentFunction($collection, $id));

        return $this;
    }
}