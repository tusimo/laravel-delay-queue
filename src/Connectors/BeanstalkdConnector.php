<?php

namespace Tusimo\DelayedQueue\Connectors;

use Tusimo\DelayedQueue\Queue\BeanstalkdQueue;
use Pheanstalk\Connection;
use Pheanstalk\Pheanstalk;
use Illuminate\Support\Arr;
use Pheanstalk\PheanstalkInterface;

class BeanstalkdConnector extends \Illuminate\Queue\Connectors\BeanstalkdConnector
{
    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        $retryAfter = Arr::get($config, 'retry_after', Pheanstalk::DEFAULT_TTR);
        $delay = Arr::get($config, 'delay', false);

        return new BeanstalkdQueue($this->pheanstalk($config), $config['queue'], $retryAfter, $delay);
    }
}
