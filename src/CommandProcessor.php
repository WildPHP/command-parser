<?php

/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Commands;

use ValidationClosures\Types;
use ValidationClosures\Utils;
use Yoshi2889\Collections\Collection;

class CommandProcessor
{
    /**
     * @var Collection
     */
    protected $commandCollection = null;

    /**
     * @var array
     */
    protected $aliases = [];

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
     * @throws \Exception
     */
    public function process(ParsedCommand $parsedCommand)
    {
        $commandObject = $this->findCommand($parsedCommand->getCommand());
        return self::processParsedCommand($parsedCommand, $commandObject);
    }

    /**
     * @param string $command
     * @param Command $commandObject
     * @param string[] $aliases
     *
     * @return bool
     */
    public function registerCommand(string $command, Command $commandObject, array $aliases = [])
    {
        if ($this->getCommandCollection()->offsetExists($command) || !Utils::validateArray(Types::string(), $aliases)) {
            return false;
        }

        $this->getCommandCollection()->offsetSet($command, $commandObject);

        foreach ($aliases as $alias) {
            $this->alias($command, $alias);
        }

        return true;
    }

    /**
     * @param string $originalCommand
     * @param string $alias
     *
     * @return bool
     */
    public function alias(string $originalCommand, string $alias): bool
    {
        if (!$this->getCommandCollection()->offsetExists($originalCommand) || array_key_exists($alias,
                $this->aliases)) {
            return false;
        }

        /** @var Command $commandObject */
        $commandObject = $this->getCommandCollection()[$originalCommand];
        $this->aliases[$alias] = $commandObject;
        return true;
    }

    /**
     * @param string $command
     *
     * @return Command|null
     */
    protected function findCommand(string $command): ?Command
    {
        $dictionary = $this->getCommandCollection();

        if (!$dictionary->offsetExists($command) && !array_key_exists($command, $this->aliases)) {
            return null;
        }

        /** @var Command $commandObject */
        return $dictionary[$command] ?? $this->aliases[$command];
    }

    /**
     * @param ParsedCommand $parsedCommand
     * @param Command $command
     * @return ProcessedCommand
     * @throws \Exception
     */
    public static function processParsedCommand(ParsedCommand $parsedCommand, Command $command): ProcessedCommand
    {
        $parameters = $parsedCommand->getArguments();
        $applicableStrategy = CommandParser::findApplicableStrategy($command, $parameters);

        return new ProcessedCommand(
            $parsedCommand->getCommand(),
            $parameters,
            $applicableStrategy,
            $applicableStrategy->convertParameterArray($parameters)
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
    public function setCommandCollection(Collection $commandCollection)
    {
        $this->commandCollection = $commandCollection;
    }
}