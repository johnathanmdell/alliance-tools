<?php
namespace App\Extensions;

use ReflectionClass;

class CrestGuzzlerFactory
{
    /**
     * @param string $resource
     * @return CrestGuzzlerFactory
     */
    public static function makeFactory($resource)
    {
        return (new ReflectionClass('App\Extensions\CrestGuzzler\\' . ucfirst($resource)))->newInstance(self::getArguments(func_get_args()));
    }

    /**
     * @param array $arguments
     * @return array
     */
    private static function getArguments(array $arguments)
    {
        array_shift($arguments);
        return $arguments;
    }
}