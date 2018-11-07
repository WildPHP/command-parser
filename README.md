# Command parsing library for WildPHP
----------
[![Build Status](https://scrutinizer-ci.com/g/WildPHP/command-parser/badges/build.png)](https://scrutinizer-ci.com/g/WildPHP/command-parser/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/WildPHP/command-parser/badges/quality-score.png)](https://scrutinizer-ci.com/g/WildPHP/command-parser/?branch=master)
[![Scrutinizer Code Coverage](https://scrutinizer-ci.com/g/WildPHP/command-parser/badges/coverage.png)](https://scrutinizer-ci.com/g/WildPHP/command-parser/code-structure/master/code-coverage)
[![Latest Stable Version](https://poser.pugx.org/wildphp/command-parser/v/stable)](https://packagist.org/packages/wildphp/command-parser)
[![Latest Unstable Version](https://poser.pugx.org/wildphp/command-parser/v/unstable)](https://packagist.org/packages/wildphp/command-parser)
[![Total Downloads](https://poser.pugx.org/wildphp/command-parser/downloads)](https://packagist.org/packages/wildphp/command-parser)


This library aims at making command parsing easy. Not only does it parse strings into commands and parameters, it allows
you to define restrictions to parameters (so-called strategies) and do automatic type/content validation.

## Installation
To install this library, you will need [Composer](https://getcomposer.org/).

    $ composer require wildphp/command-parser ^0.1
    
## Getting started
This library comes with a set of ready-to-use parameters, but you might want to develop your own. More on that later.

The most important classes in the library are the `Command`, `ParameterStrategy`, `CommandParser` and `CommandProcessor`.

### Command
This is the class which defines your commands. It is a storage class, meaning it does nothing more but store items you
hand to it.

#### Creating a Command
A `Command` takes two parameters for its constructor. A callback which should be called when the command is triggered, and
one or more `ParameterStrategy` instances which will define the behavior of the command. For example:

```php
$foo = function () {};
$command = new Command($foo, new ParameterStrategy(0, 1, [
    new NumericParameter(),
]);  
``` 

### ParameterStrategy
A `ParameterStrategy` instance describes the ways in which commands may be executed.

Its constructor takes a few arguments:

```php
__construct(
        int $minimumParameters = -1,
        int $maximumParameters = -1,
        array $initialValues = [],
        bool $implodeLeftover = false
)
```

- `$minimumParameters` and `$maximumParameters describe how many parameters this particular strategy may take.
They can be set to -1 or false to disable either bound. However, minimum cannot be larger than maximum.
- `$initialValues` is an array of `ParameterInterface` objects. More on those below.
- `$concatLeftover` is a boolean. When set to true, the strategy will concatenate all remaining parameters together into one
  when the maximum parameter count is reached. For example, consider a strategy which will only take up to 2 parameters.
  If you pass it a command with 3 parameters with this flag set, for example '!test 1 2 3', 2 and 3 will be concatenated
  as `array('1', '2 3')`
  
### Available parameters
#### NumericParameter
Takes any value which is numeric, it uses the php `is_numeric` function internally.

#### PredefinedStringParameter
Set a predefined string in its constructor and only that string will be accepted as its value, thus
any other value will be rejected.

#### StringParameter
Returns true under any circumstance, since parameters are always strings.

### CommandParser
As the name would suggest, this is the class in charge of actually parsing messages. When it does so, it hands out `ParsedCommand`
objects. As it is a utility class, its methods are called statically.

#### findApplicableStrategy
`findApplicableStrategy(Command $commandObject, array $parameters): ParameterStrategy`

This method finds the most applicable strategy to use on `$parameters` and returns it.

#### parseFromString
`parseFromString(string $string, string $prefix = '!'): ParsedCommand`

Parses a command and its parameters from a string, but does not process them. See below for the CommandProcessor.
Set `$prefix` for the prefix to use, which should prefix any command given.

`ParsedCommand` is an object containing the command name and the original parameters given.

### CommandProcessor
This class processes a ParsedCommand further and provides the value conversion facilities.
Moreover, when instantiated, it acts as a storage facility for commands so they need not be stored elsewhere.

#### processParsedCommand
The heart of this class is this static function.

`public static function processParsedCommand(ParsedCommand $parsedCommand, Command $command): ProcessedCommand`

It takes a `ParsedCommand` object and accompanying `Command` object and processes it into a `ProcessedCommand` object,
which contains the original `ParsedCommand` data plus the callback, the converted parameter values and also the applied strategy used for conversion. 

### process
`process(ParsedCommand $parsedCommand): ProcessedCommand`

Much the same as the above function but uses the internal command collection.

### registerCommand
`registerCommand(string $command, Command $commandObject): bool`

Adds a command to the internal command collection, identified by `$command`. Returns true on success, false if the given identifier
already exists.

### findCommand
`findCommand(string $command): Command`

Returns a Command object added by above function which is identified by `$command`.

## Contributors

You can see the full list of contributors [in the GitHub repository](https://github.com/WildPHP/command-parser/graphs/contributors).