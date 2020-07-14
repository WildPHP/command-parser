<?php

/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Commands;

use InvalidArgumentException;
use ValidationClosures\Types;
use ValidationClosures\Utils;

class Command
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var ParameterStrategy[]
     */
    protected $parameterStrategies;

    /**
     * Command constructor.
     *
     * @param callable $callback
     * @param array|ParameterStrategy $parameterStrategies
     */
    public function __construct(callable $callback, $parameterStrategies)
    {
        if (!is_array($parameterStrategies)) {
            $parameterStrategies = [$parameterStrategies];
        }

        $this->setParameterStrategies($parameterStrategies);
        $this->setCallback($callback);
    }

    /**
     * @return callable
     */
    public function getCallback(): callable
    {
        return $this->callback;
    }

    /**
     * @param callable $callback
     */
    public function setCallback(callable $callback): void
    {
        $this->callback = $callback;
    }

    /**
     * @return array
     */
    public function getParameterStrategies(): array
    {
        return $this->parameterStrategies;
    }

    /**
     * @param ParameterStrategy[] $parameterStrategies
     */
    public function setParameterStrategies(array $parameterStrategies): void
    {
        if (!Utils::validateArray(Types::instanceof(ParameterStrategy::class), $parameterStrategies)) {
            throw new InvalidArgumentException('Invalid array passed');
        }

        $this->parameterStrategies = $parameterStrategies;
    }
}
