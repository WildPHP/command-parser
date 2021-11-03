<?php

/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Commands;

class ParsedCommand
{

    /**
     * @var string
     */
    protected $command = '';

    /**
     * @var string[]
     */
    protected $arguments = [];

    /**
     * ParsedCommand constructor.
     *
     * @param  string  $command
     * @param  string[]  $arguments
     */
    public function __construct(string $command, array $arguments)
    {
        $this->setCommand($command);
        $this->setArguments($arguments);
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function setCommand(string $command): void
    {
        $this->command = $command;
    }

    /**
     * @return string[]
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param  string[]  $arguments
     */
    public function setArguments(array $arguments): void
    {
        $this->arguments = $arguments;
    }
}
