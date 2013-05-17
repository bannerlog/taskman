<?php

require_once __DIR__ . '/Taskman/functions.php';
require_once __DIR__ . '/Taskman/Manager.php';
require_once __DIR__ . '/Taskman/Task.php';
require_once __DIR__ . '/Taskman/Args.php';

function findTaskfile($dir)
{
    $taksFiles = [
        'taskfile',
        'Taskfile',
        'Taskfile.php',
        'taskfile.php'
    ];

    while (true) {
        foreach ($taksFiles as $taskFile) {
            $file = $dir . DIRECTORY_SEPARATOR . $taskFile;
            if (is_readable($file)) {
                require $file;

                return $file;
            }
        }

        if ($dir == '/') {
            abortTaskman("No Taskfile found (looking for: ". implode(', ', $taksFiles) .")");
        }

        $dir = dirname($dir);
    }
}

function abortTaskman($message = '')
{
    echo "taskman aborted!\n";
    exitMessage($message, 1);
}

function exitMessage($message, $s = 0)
{
    echo "$message\n";
    exit($s);
}
