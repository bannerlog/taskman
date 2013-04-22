# Taskman
Taskman is the another *ake tool written on PHP. Use it as you wish.

## Install & Run
The fastest way to install Taskman in your project is using Composer (http://getcomposer.org/).

1. Install composer:

    ```    
    curl -s https://getcomposer.org/installer | php
    ```
    
1. Add Taskman as a dependency to your `composer.json` file:

    ```js
    {
        "require": {
            "bannerlog/taskman": "*"
        }
    }
    ```
    
1. Install Taskman:
    
    ```
    php composer.phar install
    ```
    
1. Execute Taskman:
    
    ```
    php vendor/bin/taskman.php -T
    ```



## How to create task
First you need to create the task file. It could be named as:
* Taskman
* taskman
* Taskman.php
* taskman.php

It is important to remember that you can write anything you like inside a task file. But be sure that you are using valid PHP syntax.

Below goes source code of the Taskman file.

```php
<?php

desc('Simple task');
task('simple', function() {
    echo "Yeap! You have just invoked simple task!\n";
});

desc('Task with a prerequisite');
task('prerequisite', 'simple', function() {
    echo "Prerequisite task is completed.\n\n";
    echo "You can specify as many prerequisite tasks as you need.\n";
    echo "Just write them between the task name and definition of an anonymous function.\n\n";
    echo "    task(name[,'callbefore', ...], callable);\n\n";
});

group('named', function() {
    desc('Simlpe named task');
    task('task', function(){
        echo "Simple named task has been invoked.\n";
    });

    desc('Named task with a prerequisites');
    task('prerequisite', 'simple', function(){
        echo "Named prerequisite task is completed.\n\n";

        echo "If you want to call a task within the current group ";
        echo "you should place : (colon) before a prerequisite task.\n\n";
        echo "    task('name' ':callbefore', callable);\n";
        echo "    task('name' ':subgroup:anothersub:task', callable);\n\n";

        echo "If you want to call a task from a top namespace ";
        echo "you should place ^ (caret) before a prerequisite task.\n\n";
        echo "    task('name' '^callbefore', callable);\n";
        echo "    task('name' '^differentgroup:anothersubgroup:task', callable);\n\n";
    });
});

group('included', function() {
    require __DIR__ . '/tasks.php';
});
```

And now let's see included tasks from a task.php.

```php
<?php

desc('Main task');
task('main', ':prepare', function() {
    echo "Main task has been invoked\n";
});

desc('Preparation task');
task('prepare', function() {
    echo "Prepare to invoke main task\n";
});

desc('Invoke simple task from outside the group');
task('simple', '^simple', function() {
    echo "And you have been done it from included:simple.\n";
});
```

That's all for now!