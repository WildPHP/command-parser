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
use WildPHP\Commands\Exceptions\InvalidParameterCountException;
use WildPHP\Commands\Exceptions\ValidationException;
use WildPHP\Commands\Parameters\ConvertibleParameterInterface;
use WildPHP\Commands\Parameters\ParameterInterface;
use Yoshi2889\Collections\Collection;

/**
 * @extends Collection<ParameterInterface>
 */
class ParameterStrategy extends Collection
{
    /**
     * @var int
     */
    protected $minimumParameters;

    /**
     * @var int
     */
    protected $maximumParameters;

    /**
     * @var bool
     */
    protected $concatLeftover;

    /**
     * ParameterDefinitions constructor.
     *
     * @param int $minimumParameters
     * @param int $maximumParameters
     * @param ParameterInterface[] $initialValues
     * @param bool $concatLeftover
     */
    public function __construct(
        int $minimumParameters = -1,
        int $maximumParameters = -1,
        array $initialValues = [],
        bool $concatLeftover = false
    ) {
        if ($maximumParameters >= 0 && $minimumParameters > $maximumParameters) {
            throw new InvalidArgumentException('Invalid parameter range (minimum cannot be larger than maximum)');
        }

        parent::__construct(Types::instanceof(ParameterInterface::class), $initialValues);
        $this->minimumParameters = $minimumParameters;
        $this->maximumParameters = $maximumParameters;
        $this->concatLeftover = $concatLeftover;
    }

    /**
     * @param string $parameterName
     * @param string $parameterValue
     *
     * @return bool
     * @throws ValidationException
     */
    public function validateParameter(string $parameterName, string $parameterValue): bool
    {
        if (!$this->offsetExists($parameterName)) {
            throw new ValidationException('Cannot validate value for non-existing parameter ' . $parameterName);
        }

        /** @var ParameterInterface $parameter */
        $parameter = $this[$parameterName];

        return $parameter->validate($parameterValue);
    }

    /**
     * @param string[] $args
     *
     * @return bool
     * @throws \InvalidArgumentException
     * @throws InvalidParameterCountException
     * @throws ValidationException
     */
    public function validateParameterArray(array $args): bool
    {
        $names = array_keys((array)$this);

        if (!$this->validateParameterCount($args)) {
            throw new InvalidParameterCountException();
        }

        if ($this->shouldConcatLeftover()) {
            $offset = count($names) - 1;
            $args = self::implodeLeftoverParameters($args, $offset);
        }

        $index = 0;
        foreach ($args as $value) {
            if (!$this->validateParameter($names[$index], $value)) {
                return false;
            }

            $index++;
        }

        return true;
    }

    /**
     * @param string[] $args
     *
     * @return bool
     */
    public function validateParameterCount(array $args): bool
    {
        if ($this->minimumParameters < 0 || $this->shouldConcatLeftover()) {
            return true;
        }

        $count = count($args);
        $maximumParameters = $this->maximumParameters < 0 ? $count : $this->maximumParameters;

        return $count >= $this->minimumParameters && $count <= $maximumParameters;
    }

    /**
     * @param string $parameterName
     * @param string $parameterValue
     * @return mixed
     */
    public function convertParameter(string $parameterName, string $parameterValue)
    {
        if (!$this->offsetExists($parameterName)) {
            throw new InvalidArgumentException('Parameter name does not exist');
        }

        if ($this[$parameterName] instanceof ConvertibleParameterInterface) {
            return $this[$parameterName]->convert($parameterValue);
        }

        return $parameterValue;
    }

    /**
     * @param array<string, string> $parameters
     * @return array<string, string|ConvertibleParameterInterface>
     */
    public function convertParameterArray(array $parameters): array
    {
        foreach ($parameters as $name => $value) {
            $parameters[$name] = $this->convertParameter($name, $value);
        }

        return $parameters;
    }

    /**
     * @param array<int|string, mixed> $parameters
     * @return array<int|string, mixed>
     */
    public function remapNumericParameterIndexes(array $parameters): array
    {
        $index = 0;
        $remapTo = $this->keys();
        $remappedParameters = [];

        foreach ($parameters as $key => $value) {
            $newIndex = $key;

            if (is_numeric($key)) {
                $newIndex = $remapTo[$index];
            }

            $remappedParameters[$newIndex] = $value;
            $index++;
        }

        return $remappedParameters;
    }

    public function shouldConcatLeftover(): bool
    {
        return $this->concatLeftover;
    }

    public function setConcatLeftover(bool $concatLeftover): void
    {
        $this->concatLeftover = $concatLeftover;
    }

    /**
     * @param string[] $arguments
     * @param int $offset
     *
     * @return string[]
     */
    public static function implodeLeftoverParameters(array $arguments, int $offset): array
    {
        $array1 = array_slice($arguments, 0, $offset);
        $array2 = [implode(' ', array_slice($arguments, $offset))];
        return array_merge($array1, $array2);
    }
}
