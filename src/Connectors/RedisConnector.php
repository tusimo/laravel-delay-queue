<?php

namespace Tusimo\DelayedQueue\Connectors;

use Tusimo\DelayedQueue\Queue\RedisQueue;
use Illuminate\Support\Arr;

class RedisConnector extends \Illuminate\Queue\Connectors\RedisConnector
{
    public function connect(array $config)
    {
        return new RedisQueue(
            $this->redis,
            $config['queue'],
            Arr::get($config, 'connection', $this->connection),
            Arr::get($config, 'retry_after', 60),
            Arr::get($config, 'delay', false)
        );
    }
}
