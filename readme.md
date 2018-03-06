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
## 注意
默认CLI模式会在错误产生的时候自动删除已经发出的job保证只有在运行成功后才会触发job到队列。非CLI模式没有处理，需要自己在类似删除report函数里清空。
如果在错误发生时，也需要某个job进入队列执行，请手动在代码里运行 ```php DelayQueueContainer::fireQueueJobs();```,此时会立即将已保存的job发送到队里。
