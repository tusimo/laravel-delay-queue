# LaravelDelayQueue

## 安装

1. 修改composer.json
```json
{
    "require":
    {
        "tusimo/laravel-delay-queue": "dev-master"
    }
}
```

2. 修改config/app.php
```php
<?php
return [
    'providers' => [
        /*
         * Package Service Providers...
         */
        Tusimo\DelayedQueue\Provider\DelayedQueueServiceProvider::class,
    ]
];
```

3. 修改config/queue.php.增加delay参数为true即可,其他connection也可使用delay
```php
'redis' => [
            'driver'      => 'redis',
            'connection'  => 'default',
            'queue'       => 'default',
            'retry_after' => 60,
            'delay'       => true
        ]
```

## 使用
当使用event异步处理，job时不会立即发送到后台处理
默认会在register_shutdown_function回调时发送所有队列任务和job.

如果在发生异常时，不希望触发后台处理，可以在以下地方将本次请求中所有产生的异步任务清空。
```php
public function report(Exception $exception)
    {
        //清空已经保存的队列任务
        DelayQueueContainer::flushQueueJobs();
        
        //获取已保存的队列任务
        DelayQueueContainer::getQueueJobs();
        
        //立即将已保存的队列任务放入队列后台
        DelayQueueContainer::fireQueueJobs()
        
        if ($this->shouldntReport($exception)) {
            return;
        }
        app(ErrorReporter::class)->report($exception);
    }
```
