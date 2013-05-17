<?php
namespace Taskman;

const VERSION = '0.5';

class Manager
{
    private static $instance;
    private $tasks = [];
    private $description;
    private $scope = [];

    private function __construct()
    {

    }

    public static function getInstance()
    {
        if (! self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function addTask($name, $job, $dependencies = null)
    {
        if (!is_callable($job)) {
            throw new \Exception("Task $name job is not callable");
        }

        $scope = $this->scope;
        array_push($scope, $name);
        $this->tasks[implode(':', $scope)] = new Task(
            $job,
            $dependencies,
            $this->description
        );

        $this->description = null;
    }

    public function tasks()
    {
        return $this->tasks;
    }

    public function invoke($name, array $args = [])
    {
        if (! isset($this->tasks[$name])) {
            throw new \Exception("Task '$name' not found");
        }

        $task = $this->tasks[$name];
        foreach ($task->getDependencies() as $dependency) {
            if (strpos($dependency, ':') === 0) {
                if (strrpos($name, ':') > 0) {
                    $dependency = substr($name, 0, strrpos($name, ":")) . $dependency;
                } else {
                    $dependency = substr($dependency, 1);
                }
            } elseif (strpos($dependency, '^') === 0) {
                preg_match('/^(\^*)(.*)/', $dependency, $matches);
                $dependency = array_slice(explode(':', $name), 0, (strlen($matches[1])+1) * -1);
                $dependency[] = $matches[2];
                $dependency = implode(':', $dependency);
            }
            $this->invoke($dependency, $args);
        }

        $task->run($args);
    }

    public function setDescription($desciption)
    {
        $this->description = $desciption;
    }

    public function buildScope($name)
    {
        $this->scope[] = $name;
    }

    public function popScope()
    {
        array_pop($this->scope);
    }
}
