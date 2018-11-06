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
    protected $convertedArguments = [];

    /**
     * ProcessedCommand constructor.
     * @param string $command
     * @param array $arguments
     * @param ParameterStrategy $applicableStrategy
     * @param array $convertedArguments
     */
    public function __construct(string $command, array $arguments, ParameterStrategy $applicableStrategy, array $convertedArguments)
    {
        parent::__construct($command, $arguments);
        $this->convertedArguments = $convertedArguments;
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
    public function getConvertedArguments(): array
    {
        return $this->convertedArguments;
    }
}