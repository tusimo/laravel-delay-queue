<?php

namespace Tusimo\DelayedQueue\Connectors;

use Tusimo\DelayedQueue\Queue\DatabaseQueue;
use Illuminate\Support\Arr;

class DatabaseConnector extends \Illuminate\Queue\Connectors\DatabaseConnector
{
    public function connect(array $config)
    {
        return new DatabaseQueue(
            $this->connections->connection(Arr::get($config, 'connection')),
            $config['table'],
            $config['queue'],
            Arr::get($config, 'retry_after', 60),
            Arr::get($config, 'delay', false)
        );
    }
}
