#!/usr/bin/env php
<?php

require_once __DIR__ .'/../src/bootstrap.php';

error_reporting(-1);

if (function_exists('ini_set')) {
    @ini_set('display_errors', 1);
}

$opt = getopt(
    'f:Th',
    ['taskfile:', 'tasks', 'help', 'version']
);

if (isset($opt['h']) || isset($opt['help'])) {
    $usage = <<<HELP
usage: taskman [-f taskfile] {options} <target> [<args>]

Options:
    -f, --taskfile  Use FILE as the taskfile
    -T, --tasks     Display the tasks with descriptions, then exit
        --version   Display the program version
HELP;
    exitMessage($usage);
}

if (isset($opt['version'])) {
    exitMessage("This is Taskman v". Taskman\VERSION);
}

try {
    if (isset($opt['f'])) {
        $taskfile = $opt['f'];
    }
    elseif (isset($opt['taskfile'])) {
        $taskfile = $opt['taskfile'];
    } else {
        $taskfile = null;
    }

    if ($taskfile) {
        if (!is_readable($taskfile)) {
            abortTaskman("No Taskfile found (looking for: $taskfile)");
        }

        if (is_file($taskfile)) {
            require $taskfile;
        } else {
            $taskfile = findTaskfile($taskfile);
        }

        $cmd = isset($argv[3]) ? $argv[3] : null;
        $taskArgs = array_slice($argv, 4);
    } else {
        $taskfile = findTaskfile(getcwd());
        $cmd = isset($argv[1]) ? $argv[1] : null;
        $taskArgs = array_slice($argv, 2);
    }

    echo "(in $taskfile)\n";

    if (isset($opt['T']) || isset($opt['tasks'])) {
        $tasks = Taskman\Manager::getInstance()->tasks();
        ksort($tasks);
        $max = max(array_map('strlen', array_keys($tasks)));

        foreach ($tasks as $name => $task) {
            echo str_pad($name, $max + 4) . $task . "\n";
        }
        exit;
    }

    Taskman\Args::init($taskArgs);
    Taskman\Manager::getInstance()->invoke(
        empty($cmd) ? 'default' : $cmd,
        $taskArgs
    );
} catch (Exception $e) {
    abortTaskman($e->getMessage());
}
