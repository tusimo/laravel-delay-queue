<?php

namespace Tusimo\DelayedQueue\Connectors;

use Tusimo\DelayedQueue\Queue\SyncQueue;

class SyncConnector extends \Illuminate\Queue\Connectors\SyncConnector
{
    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        return new SyncQueue();
    }
}
