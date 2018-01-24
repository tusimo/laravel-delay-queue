<?php

namespace Tusimo\DelayedQueue\Connectors;

use Illuminate\Queue\NullQueue;

class NullConnector extends \Illuminate\Queue\Connectors\NullConnector
{
    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        return new NullQueue();
    }
}
