<?php

namespace Tusimo\DelayedQueue\Queue;

use Aws\Sqs\SqsClient;

class SqsQueue extends \Illuminate\Queue\SqsQueue
{
    use DelayedQueueHelper;

    /**
     * Create a new Amazon SQS queue instance.
     *
     * @param  \Aws\Sqs\SqsClient $sqs
     * @param  string $default
     * @param  string $prefix
     * @param bool $delay
     */
    public function __construct(SqsClient $sqs, $default, $prefix = '', $delay = false)
    {
        $this->setDelay($delay);
        parent::__construct($sqs, $default, $prefix);
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
     * Push a new job onto the queue after a delay.
     *
     * @param  \DateTime|int  $delay
     * @param  string  $job
     * @param  mixed   $data
     * @param  string  $queue
     * @return mixed
     */
    public function later($delay, $job, $data = '', $queue = null)
    {
        if ($this->getDelay()) {
            $this->addQueueJobs('laterRaw', func_get_args());
            return 0;
        } else {
            return parent::later($delay, $job, $data, $queue);
        }
    }
}
