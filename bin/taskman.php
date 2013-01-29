#!/usr/bin/env php
<?php

require_once __DIR__ .'/../src/bootstrap.php';

error_reporting(-1);

if (function_exists('ini_set')) {
    @ini_set('display_errors', 1);
}

try {
    $taskfile = isset($argv[1]) && $argv[1] == '-f';

    if ($taskfile) {
        $cmd = isset($argv[3]) ? $argv[3] : null;
        $taskArgs = array_slice($argv, 4);
    } else {
        $cmd = isset($argv[1]) ? $argv[1] : null;
        $taskArgs = array_slice($argv, 2);
    }

    if ($taskfile && !empty($argv[2])) {
        if (!is_readable($argv[2])) {
            abortTaskman("No Taskfile found (looking for: {$argv[2]})");
        }

        if (is_file($argv[2])) {
            $taskfile = $argv[2];
            require $argv[2];
        } else {
            $taskfile = findTaskfile($argv[2]);
        }
    } else {
        $taskfile = findTaskfile(getcwd());
    }

    echo "(in $taskfile)\n";

    switch ($cmd) {
        case '--version':
            echo "This is Taskman v". Taskman\VERSION ."\n";
            break;
        case '-T':
        case '--tasks':
            $tasks = Taskman\Manager::getInstance()->tasks();
            ksort($tasks);
            $max = max(array_map('strlen', array_keys($tasks)));

            foreach ($tasks as $name => $task) {
                echo str_pad($name, $max + 4) . $task . "\n";
            }
            break;
        default:
            Taskman\Manager::getInstance()->invoke(
                empty($cmd) ? 'default' : $cmd,
                $taskArgs
            );
    }
} catch (Exception $e) {
    echo "taskman aborted!\n", $e->getMessage(), "\n";
}
