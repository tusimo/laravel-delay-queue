<?php

namespace Tusimo\DelayedQueue\Queue;

class SyncQueue extends \Illuminate\Queue\SyncQueue
{
    use DelayedQueueHelper;
}
