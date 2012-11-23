<?php
namespace Taskman;

class Task
{
    private $job;
    private $dependencies;
    private $description;

    public function __construct($job, $dependencies = [], $description = null)
    {
        $this->job = $job;
        $this->dependencies = $dependencies;
        $this->description = $description;
    }

    public function getDependencies()
    {
        return $this->dependencies;
    }

    public function run(array $args = [])
    {
        call_user_func_array($this->job, $args);
    }

    public function __toString()
    {
        return (string) $this->description;
    }
}
