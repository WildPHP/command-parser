<?php

/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Commands;


class ProcessedCommand extends ParsedCommand
{
    /**
     * @var ParameterStrategy
     */
    protected $applicableStrategy = null;

    /**
     * @var array
     */
    protected $convertedParameters = [];

    /**
     * @var callable
     */
    protected $callback;

    /**
     * ProcessedCommand constructor.
     * @param string $command
     * @param array $arguments
     * @param ParameterStrategy $applicableStrategy
     * @param array $convertedParameters
     * @param callable $callback
     */
    public function __construct(string $command, array $arguments, ParameterStrategy $applicableStrategy, array $convertedParameters, callable $callback)
    {
        parent::__construct($command, $arguments);
        $this->convertedParameters = $convertedParameters;
        $this->applicableStrategy = $applicableStrategy;
        $this->callback = $callback;
    }

    /**
     * @return ParameterStrategy
     */
    public function getApplicableStrategy(): ParameterStrategy
    {
        return $this->applicableStrategy;
    }

    /**
     * @return array
     */
    public function getConvertedParameters(): array
    {
        return $this->convertedParameters;
    }

    /**
     * @return callable
     */
    public function getCallback(): callable
    {
        return $this->callback;
    }
}