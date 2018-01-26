<?php

namespace Tusimo\DelayedQueue\Connectors;

use Illuminate\Queue\Connectors\ConnectorInterface;
use Illuminate\Support\Arr;
use Tusimo\DelayedQueue\Queue\DelayQueue;

class DelayConnector implements ConnectorInterface
{
    protected $connector;

    public function __construct(ConnectorInterface $connector)
    {
        $this->connector = $connector;
    }

    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        if ($delay = Arr::get($config, 'delay', false)) {
            return new DelayQueue($this->connector->connect($config), $delay);
        } else {
            return $this->connector->connect($config);
        }
    }
}
