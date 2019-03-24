<?php
namespace LaravelFreelancerNL\FluentAQL\API;

/**
 * Trait hasFunctions
 * API calls to add AQL function commands to the builder.
 *
 * @package LaravelFreelancerNL\FluentAQL\API
 */
trait hasFunctions
{

    public function document($collection, $id)
    {
        return "DOCUMENT($collection, $id)";
    }
}