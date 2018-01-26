<?php

namespace Tusimo\DelayedQueue\Queue;

use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\Queue as QueueContract;
use Illuminate\Queue\Queue;
use Tusimo\DelayedQueue\DelayQueueContainer;

class DelayQueue extends Queue implements QueueContract
{
    protected $queue;

    protected $delay = false;

    public function __construct(QueueContract $queue, $delay = false)
    {
        $this->queue = $queue;
        $this->setDelay($delay);
    }

    /**
     * Get the size of the queue.
     *
     * @param  string $queue
     * @return int
     */
    public function size($queue = null)
    {
        return $this->queue->size($queue);
    }

    /**
     * Push a new job onto the queue.
     *
     * @param  string|object $job
     * @param  mixed $data
     * @param  string $queue
     * @return mixed
     */
    public function push($job, $data = '', $queue = null)
    {
        if ($this->getDelay()) {
            $this->addQueueJobs('push', func_get_args());
            return 0;
        } else {
            return $this->queue->push($job, $data, $queue);
        }
    }

    /**
     * Push a new job onto the queue.
     *
     * @param  string  $queue
     * @param  string|object  $job
     * @param  mixed   $data
     * @return mixed
     */
    public function pushOn($queue, $job, $data = '')
    {

        if ($this->getDelay()) {
            $this->addQueueJobs('pushOn', func_get_args());
            return 0;
        } else {
            return $this->queue->pushOn($queue, $job, $data);
        }
    }

    /**
     * Push a raw payload onto the queue.
     *
     * @param  string $payload
     * @param  string $queue
     * @param  array $options
     * @return mixed
     */
    public function pushRaw($payload, $queue = null, array $options = [])
    {
        if ($this->getDelay()) {
            $this->addQueueJobs('pushRaw', func_get_args());
            return 0;
        } else {
            return $this->queue->pushRaw($payload, $queue, $options);
        }
    }

    /**
     * Push a new job onto the queue after a delay.
     *
     * @param  \DateTimeInterface|\DateInterval|int $delay
     * @param  string|object $job
     * @param  mixed $data
     * @param  string $queue
     * @return mixed
     */
    public function later($delay, $job, $data = '', $queue = null)
    {

        if ($this->getDelay()) {
            $this->addQueueJobs('later', func_get_args());
            return 0;
        } else {
            return $this->queue->later($delay, $job, $data, $queue);
        }
    }

    /**
     * Push a new job onto the queue after a delay.
     *
     * @param  string  $queue
     * @param  \DateTimeInterface|\DateInterval|int  $delay
     * @param  string|object  $job
     * @param  mixed   $data
     * @return mixed
     */
    public function laterOn($queue, $delay, $job, $data = '')
    {

        if ($this->getDelay()) {
            $this->addQueueJobs('laterOn', func_get_args());
            return 0;
        } else {
            return $this->queue->laterOn($queue, $delay, $job, $data);
        }
    }

    /**
     * Pop the next job off of the queue.
     *
     * @param  string $queue
     * @return \Illuminate\Contracts\Queue\Job|null
     */
    public function pop($queue = null)
    {
        return $this->queue->pop($queue);
    }

    /**
     * Push an array of jobs onto the queue.
     *
     * @param  array $jobs
     * @param  mixed $data
     * @param  string $queue
     * @return mixed
     */
    public function bulk($jobs, $data = '', $queue = null)
    {
        if ($this->getDelay()) {
            $this->addQueueJobs('bulk', func_get_args());
            return 0;
        } else {
            return $this->queue->bulk($jobs, $data, $queue);
        }
    }

    public function __call($name, $arguments)
    {
        $return  = $this->queue->$name(...$arguments);
        if ($return instanceof $this->queue) {
            return $this;
        } else {
            return $return;
        }
    }

    public function realPush($method, $parameters)
    {
        return $this->queue->$method(...$parameters);
    }

    /**
     * Set the IoC container instance.
     *
     * @param  \Illuminate\Container\Container  $container
     * @return void
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
        $this->queue->setContainer($container);
    }

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
