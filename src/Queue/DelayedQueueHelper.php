<?php

namespace Tusimo\DelayedQueue\Queue;

trait DelayedQueueHelper
{
    protected $delay = false;

    protected $queueJobs = [];

    public function setDelay($delay = false)
    {
        $this->delay = $delay;
    }

    public function getDelay()
    {
        return $this->delay;
    }

    protected function addQueueJobs($function, $args)
    {
        if (isset($this->queueJobs[$function])) {
            array_push($this->queueJobs[$function], $args);
        } else {
            $this->queueJobs[$function][] = $args;
        }
    }

    public function fireQueueJobs()
    {
        if ($this->queueJobs) {
            foreach ($this->queueJobs as $function => $jobs) {
                foreach ($jobs as $job) {
                    parent::$function(...$job);
                }
            }
        }
        $this->flushQueueJobs();
    }

    public function flushQueueJobs()
    {
        $this->queueJobs = [];
    }
}
