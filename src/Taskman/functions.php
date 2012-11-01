<?php

function task()
{
    $args = func_get_args();

    Taskman\Manager::getInstance()->addTask(
        array_shift($args),
        $args[count($args) - 1] instanceof Closure ? array_pop($args) : null,
        $args
    );
}

function desc($description)
{
    Taskman\Manager::getInstance()->setDescription($description);
}

function group($name, Closure $context)
{
    Taskman\Manager::getInstance()->buildScope($name);
    $context();
    Taskman\Manager::getInstance()->clearScope();
}
