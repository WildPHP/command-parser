<?php

/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Commands;

use WildPHP\Commands\Exceptions\NoApplicableStrategiesException;
use WildPHP\Commands\Exceptions\ParseException;

class CommandParser
{
    /**
     * @param Command $commandObject
     * @param array $parameters
     *
     * @return ParameterStrategy
     * @throws Exceptions\InvalidParameterCountException
     * @throws Exceptions\ValidationException
     * @throws NoApplicableStrategiesException
     */
    public static function findApplicableStrategy(Command $commandObject, array $parameters): ParameterStrategy
    {
        $parameterStrategies = $commandObject->getParameterStrategies();

        /** @var ParameterStrategy $parameterStrategy */
        foreach ($parameterStrategies as $parameterStrategy) {
            $result = $parameterStrategy->validateParameterArray($parameters);

            if ($result) {
                return $parameterStrategy;
            }
        }

        throw new NoApplicableStrategiesException('No applicable strategies were found');
    }

    /**
     * @param string $string
     * @param string $prefix
     * @return ParsedCommand
     * @throws ParseException
     */
    public static function parseFromString(string $string, string $prefix = '!'): ParsedCommand
    {
        $messageParts = explode(' ', trim($string));
        $firstPart = array_shift($messageParts);

        if (strlen($firstPart) === strlen($prefix)) {
            throw new ParseException('This command consists of only a command prefix');
        }

        if (strpos($firstPart, $prefix) !== 0) {
            throw new ParseException('The command prefix is not at the beginning of the given command');
        }

        $command = substr($firstPart, strlen($prefix));

        // Remove empty elements and excessive spaces.
        $parameters = array_values(
            array_map(
                'trim',
                array_filter(
                    $messageParts,
                    static function ($parameter) {
                        return !preg_match('/^$|\s/', $parameter);
                    }
                )
            )
        );

        return new ParsedCommand($command, $parameters);
    }
}
