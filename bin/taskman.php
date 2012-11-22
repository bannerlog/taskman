#!/usr/bin/env php
<?php

require_once __DIR__ .'/../src/bootstrap.php';

$options = 'T';
$longopts = [
    'tasks',
    'version'
];

error_reporting(-1);

if (function_exists('ini_set')) {
    @ini_set('display_errors', 1);

    $memoryInBytes = function ($value) {
        $unit = strtolower(substr($value, -1, 1));
        $value = (int)$value;
        switch ($unit) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }

        return $value;
    };

    $memoryLimit = trim(ini_get('memory_limit'));
    // Increase memory_limit if it is lower than 512M
    if ($memoryLimit != -1 && $memoryInBytes($memoryLimit) < 512 * 1024 * 1024) {
        @ini_set('memory_limit', '512M');
    }
    unset($memoryInBytes, $memoryLimit);
}

try {
    echo "(in " . findTaskfile(getcwd()) .")\n";

    $opts = array_keys(getopt($options, $longopts));
    switch (!empty($opts) ? $opts[0] : null) {
        case 'version':
            echo "This is Taskman\n";
            break;
        case 'T':
        case 'tasks':
            $tasks = Taskman\Manager::getInstance()->tasks();
            ksort($tasks);
            $max = max(array_map('strlen', array_keys($tasks)));

            foreach ($tasks as $name => $task) {
                echo str_pad($name, $max + 4) . $task . "\n";
            }
            break;
        default:
            Taskman\Manager::getInstance()->invoke(
                empty($argv[1]) ? 'default' : $argv[1]
            );
    }
} catch (Exception $e) {
    echo "taskman aborted!\n", $e->getMessage(), "\n";
}
