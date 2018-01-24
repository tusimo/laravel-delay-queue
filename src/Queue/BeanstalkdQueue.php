<?php

namespace Tusimo\DelayedQueue\Queue;

use Pheanstalk\Pheanstalk;

class BeanstalkdQueue extends \Illuminate\Queue\BeanstalkdQueue
{
    use DelayedQueueHelper;

    /**
     * Create a new Beanstalkd queue instance.
     *
     * @param  \Pheanstalk\Pheanstalk $pheanstalk
     * @param  string $default
     * @param  int $timeToRun
     * @param bool $delay
     */
    public function __construct(Pheanstalk $pheanstalk, $default, $timeToRun, $delay = false)
    {
        $this->setDelay($delay);
        parent::__construct($pheanstalk, $default, $timeToRun);
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
            $this->addQueueJobs('pushRaw', func_get_args());
            return 0;
        } else {
            return parent::later($delay, $job, $data, $queue);
        }
    }
}
