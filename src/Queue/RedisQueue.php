<?php

namespace Tusimo\DelayedQueue\Queue;

use Illuminate\Contracts\Redis\Factory as Redis;

class RedisQueue extends \Illuminate\Queue\RedisQueue
{
    use DelayedQueueHelper;

    /**
     * Create a new Redis queue instance.
     *
     * @param  \Illuminate\Contracts\Redis\Factory $redis
     * @param  string $default
     * @param  string $connection
     * @param  int $retryAfter
     * @param bool $delay
     */
    public function __construct(
        Redis $redis,
        $default = 'default',
        $connection = null,
        $retryAfter = 60,
        $delay = false
    ) {
        $this->setDelay($delay);
        parent::__construct($redis, $default, $connection, $retryAfter);
    }

    /**
     * Push a raw payload onto the queue.
     *
     * @param  string  $payload
     * @param  string  $queue
     * @param  array   $options
     * @return mixed
     */
    public function pushRaw($payload, $queue = null, array $options = [])
    {
        if ($this->getDelay()) {
            $this->addQueueJobs('pushRaw', func_get_args());
            return 0;
        } else {
            return parent::pushRaw($payload, $queue, $options);
        }
    }

    /**
     * Push a raw job onto the queue after a delay.
     *
     * @param  \DateTime|int  $delay
     * @param  string  $payload
     * @param  string  $queue
     * @return mixed
     */
    protected function laterRaw($delay, $payload, $queue = null)
    {
        if ($this->getDelay()) {
            $this->addQueueJobs('laterRaw', func_get_args());
            return 0;
        } else {
            return parent::laterRaw($delay, $payload, $queue);
        }
    }
}
