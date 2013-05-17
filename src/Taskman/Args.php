<?php
namespace Taskman;

class Args
{
    private static $args = [];

    public static function init($argv)
    {
        self::$args = [];

        foreach ($argv as $v) {
            if (($eq = strpos($v, '=')) !== false) {
                self::$args[substr($v, 0, $eq)] = substr($v, $eq + 1);
            } else {
                self::$args[$v] = true;
            }
        }
    }

    public static function has($name)
    {
        return array_key_exists($name, self::$args);
    }

    public static function get($name)
    {
        return self::has($name) ? self::$args[$name] : null;
    }

    public static function all()
    {
        return self::$args;
    }
}
