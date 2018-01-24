<?php

namespace Tusimo\DelayedQueue\Connectors;

use Tusimo\DelayedQueue\Queue\SqsQueue;
use Aws\Sqs\SqsClient;
use Illuminate\Support\Arr;

class SqsConnector extends \Illuminate\Queue\Connectors\SqsConnector
{
    public function connect(array $config)
    {
        $config = $this->getDefaultConfiguration($config);

        if ($config['key'] && $config['secret']) {
            $config['credentials'] = Arr::only($config, ['key', 'secret']);
        }

        return new SqsQueue(
            new SqsClient($config), $config['queue'],
            Arr::get($config, 'prefix', ''),
            Arr::get($config, 'delay', false)
        );
    }
}
