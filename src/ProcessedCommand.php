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
     * ProcessedCommand constructor.
     * @param string $command
     * @param array $arguments
     * @param ParameterStrategy $applicableStrategy
     * @param array $convertedParameters
     */
    public function __construct(string $command, array $arguments, ParameterStrategy $applicableStrategy, array $convertedParameters)
    {
        parent::__construct($command, $arguments);
        $this->convertedParameters = $convertedParameters;
        $this->applicableStrategy = $applicableStrategy;
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
}