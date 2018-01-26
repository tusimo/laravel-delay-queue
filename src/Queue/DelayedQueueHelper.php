<?php

namespace Tusimo\DelayedQueue\Queue;

use Tusimo\DelayedQueue\DelayQueueContainer;

trait DelayedQueueHelper
{
    protected $queue;

    protected $delay = false;

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
        DelayQueueContainer::addQueueJobs($this, $function, $args);
    }
}
