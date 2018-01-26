<?php

namespace Tusimo\DelayedQueue;

use Illuminate\Contracts\Queue\Queue;

class DelayQueueContainer
{
    private static $queueJobs = [];

    public static function addQueueJobs(Queue $queue, $function, $args)
    {
        self::$queueJobs[] = [
            'queue' => $queue,
            'function' => $function,
            'args' => $args
        ];
    }

    public static function fireQueueJobs()
    {
        if (self::$queueJobs) {
            foreach (self::$queueJobs as $queueJob) {
                $queue = $queueJob['queue'];
                $queue->realPush($queueJob['function'], $queueJob['args']);
            }
        }
        self::flushQueueJobs();
    }

    public static function flushQueueJobs()
    {
        self::$queueJobs = [];
    }

    public static function getQueueJobs()
    {
        return self::$queueJobs;
    }
}
