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

    public function run()
    {
        if (is_callable($this->job)) {
            call_user_func($this->job);
        }
    }

    public function __toString()
    {
        return (string) $this->description;
    }
}
