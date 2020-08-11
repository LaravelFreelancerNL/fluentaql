<?php

namespace LaravelFreelancerNL\FluentAQL;

abstract class QueryElement
{
    /**
     * Compile expression output.
     *
     * @return string
     */
    public function compile()
    {
    }

    /**
     * Get expression.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->compile();
    }
}
