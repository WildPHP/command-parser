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
     * @var mixed[]
     */
    protected $convertedParameters = [];

    /**
     * @var callable
     */
    protected $callback;

    /**
     * ProcessedCommand constructor.
     * @param string $command
     * @param string[] $arguments
     * @param ParameterStrategy $applicableStrategy
     * @param mixed[] $convertedParameters
     * @param callable $callback
     */
    public function __construct(
        string $command,
        array $arguments,
        ParameterStrategy $applicableStrategy,
        array $convertedParameters,
        callable $callback
    ) {
        parent::__construct($command, $arguments);
        $this->convertedParameters = $convertedParameters;
        $this->applicableStrategy = $applicableStrategy;
        $this->callback = $callback;
    }

    public function getApplicableStrategy(): ParameterStrategy
    {
        return $this->applicableStrategy;
    }

    /**
     * @return mixed[]
     */
    public function getConvertedParameters(): array
    {
        return $this->convertedParameters;
    }

    public function getCallback(): callable
    {
        return $this->callback;
    }
}
