<?php

require_once __DIR__ . '/Taskman/functions.php';
require_once __DIR__ . '/Taskman/Manager.php';
require_once __DIR__ . '/Taskman/Task.php';

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
            echo "taskman aborted!\n";
            echo "No Taskfile found (looking for: ". implode(', ', $taksFiles) .")\n";
            exit;
        }

        $dir = dirname($dir);
    }
}
