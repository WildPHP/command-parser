<?php

/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Commands;

use ValidationClosures\Types;
use WildPHP\Commands\Exceptions\CommandNotFoundException;
use Yoshi2889\Collections\Collection;

class CommandProcessor
{
    /**
     * @var Collection
     */
    protected $commandCollection;

    /**
     * CommandProcessor constructor.
     * @param Command[] $initialValues
     */
    public function __construct(array $initialValues = [])
    {
        $this->setCommandCollection(new Collection(Types::instanceof(Command::class), $initialValues));
    }

    /**
     * @param ParsedCommand $parsedCommand
     * @return ProcessedCommand
     * @throws Exceptions\CommandNotFoundException
     * @throws Exceptions\InvalidParameterCountException
     * @throws Exceptions\NoApplicableStrategiesException
     * @throws Exceptions\ValidationException
     */
    public function process(ParsedCommand $parsedCommand): ProcessedCommand
    {
        $commandObject = $this->findCommand($parsedCommand->getCommand());
        return self::processParsedCommand($parsedCommand, $commandObject);
    }

    /**
     * @param string $command
     * @param Command $commandObject
     *
     * @return bool
     */
    public function registerCommand(string $command, Command $commandObject): bool
    {
        if ($this->getCommandCollection()->offsetExists($command)) {
            return false;
        }

        $this->getCommandCollection()->offsetSet($command, $commandObject);

        return true;
    }

    /**
     * @param string $command
     *
     * @return Command
     * @throws CommandNotFoundException
     */
    public function findCommand(string $command): Command
    {
        $dictionary = $this->getCommandCollection();

        if (!$dictionary->offsetExists($command)) {
            throw new CommandNotFoundException('The given command was not (yet) registered in the CommandProcessor');
        }

        /** @var Command $commandObject */
        return $dictionary[$command];
    }

    /**
     * @param ParsedCommand $parsedCommand
     * @param Command $command
     * @return ProcessedCommand
     * @throws Exceptions\InvalidParameterCountException
     * @throws Exceptions\NoApplicableStrategiesException
     * @throws Exceptions\ValidationException
     */
    public static function processParsedCommand(ParsedCommand $parsedCommand, Command $command): ProcessedCommand
    {
        $parameters = $parsedCommand->getArguments();
        $applicableStrategy = CommandParser::findApplicableStrategy($command, $parameters);

        return new ProcessedCommand(
            $parsedCommand->getCommand(),
            $parameters,
            $applicableStrategy,
            $applicableStrategy->convertParameterArray($parameters),
            $command->getCallback()
        );
    }

    /**
     * @return Collection
     */
    public function getCommandCollection(): Collection
    {
        return $this->commandCollection;
    }

    /**
     * @param Collection $commandCollection
     */
    public function setCommandCollection(Collection $commandCollection): void
    {
        $this->commandCollection = $commandCollection;
    }
}
