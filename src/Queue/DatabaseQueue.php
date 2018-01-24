<?php

namespace Tusimo\DelayedQueue\Queue;

use Illuminate\Database\Connection;

class DatabaseQueue extends \Illuminate\Queue\DatabaseQueue
{
    use DelayedQueueHelper;

    /**
     * Create a new database queue instance.
     *
     * @param  \Illuminate\Database\Connection $database
     * @param  string $table
     * @param  string $default
     * @param  int $retryAfter
     * @param bool $delay
     */
    public function __construct(Connection $database, $table, $default = 'default', $retryAfter = 60, $delay = false)
    {
        $this->setDelay($delay);
        parent::__construct($database, $table, $default, $retryAfter);
    }

    /**
     * Push a raw payload to the database with a given delay.
     *
     * @param  string|null  $queue
     * @param  string  $payload
     * @param  \DateTime|int  $delay
     * @param  int  $attempts
     * @return mixed
     */
    protected function pushToDatabase($queue, $payload, $delay = 0, $attempts = 0)
    {
        if ($this->getDelay()) {
            $this->addQueueJobs('laterRaw', func_get_args());
            return 0;
        } else {
            return parent::pushToDatabase($queue, $payload, $delay, $attempts);
        }
    }
}
