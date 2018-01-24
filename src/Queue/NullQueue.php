<?php

namespace Tusimo\DelayedQueue\Queue;

class NullQueue extends \Illuminate\Queue\NullQueue
{
    use DelayedQueueHelper;
}
