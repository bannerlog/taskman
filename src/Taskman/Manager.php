<?php
namespace Taskman;

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

    public function invoke($name)
    {
        if (! isset($this->tasks[$name])) {
            throw new \Exception("Task '$name' not found");
        }

        $task = $this->tasks[$name];
        foreach ($task->getDependencies() as $dependency) {
            if (strpos($dependency, ':') === 0) {
                $dependency = substr($name, 0, strrpos($name, ":")) . $dependency;
            }
            $this->invoke($dependency);
        }

        $task->run();
    }

    public function setDescription($desciption)
    {
        $this->description = $desciption;
    }

    public function buildScope($name)
    {
        $this->scope[] = $name;
    }

    public function clearScope()
    {
        $this->scope = [];
    }
}
