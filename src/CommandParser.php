<?php

/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Commands;

class CommandParser
{
    /**
     * @param Command $commandObject
     * @param array $parameters
     *
     * @return null|ParameterStrategy
     * @throws \Exception
     */
    public static function findApplicableStrategy(Command $commandObject, array $parameters): ?ParameterStrategy
    {
        $parameterStrategies = $commandObject->getParameterStrategies();

        /** @var ParameterStrategy $parameterStrategy */
        foreach ($parameterStrategies as $parameterStrategy) {
            try {
                $parameterStrategy->validateArgumentArray($parameters);
                return $parameterStrategy;
            } catch (\InvalidArgumentException $e) {
                // Nothing is done here
            }
        }

        return null;
    }

    /**
     * @param string $message
     * @param string $prefix
     * @return ParsedCommand
     */
    public static function parseFromString(string $message, string $prefix = '!'): ?ParsedCommand
    {
        $messageParts = explode(' ', trim($message));
        $firstPart = array_shift($messageParts);

        if (strlen($firstPart) == strlen($prefix)) {
            return null;
        }

        if (substr($firstPart, 0, strlen($prefix)) != $prefix) {
            return null;
        }

        $command = substr($firstPart, strlen($prefix));

        // Remove empty elements and excessive spaces.
        $parameters = array_values(array_map('trim', array_filter($messageParts, function ($parameter) {
            return !preg_match('/^$|\s/', $parameter);
        })));

        $parsedCommand = new ParsedCommand($command, $parameters);

        return $parsedCommand;
    }
}