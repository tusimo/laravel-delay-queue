<?php

namespace Tusimo\DelayedQueue\Provider;

use Tusimo\DelayedQueue\Connectors\BeanstalkdConnector;
use Tusimo\DelayedQueue\Connectors\DatabaseConnector;
use Tusimo\DelayedQueue\Connectors\RedisConnector;
use Tusimo\DelayedQueue\Connectors\SqsConnector;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Queue;

class DelayedQueueServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConnectors(app('queue'));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        register_shutdown_function(function () {
            register_shutdown_function(function () {
                $queue = $this->app['queue'];
                if ($queue instanceof Queue) {
                    return;
                }
                $this->app['queue']->fireQueueJobs();
            });
        });
    }

    /**
     * Register the connectors on the queue manager.
     *
     * @param  \Illuminate\Queue\QueueManager  $manager
     * @return void
     */
    public function registerConnectors($manager)
    {
        foreach (['Database', 'Redis', 'Beanstalkd', 'Sqs'] as $connector) {
            $this->{"register{$connector}Connector"}($manager);
        }
    }

    /**
     * Register the database queue connector.
     *
     * @param  \Illuminate\Queue\QueueManager  $manager
     * @return void
     */
    protected function registerDatabaseConnector($manager)
    {
        $manager->addConnector('database', function () {
            return new DatabaseConnector($this->app['db']);
        });
    }

    /**
     * Register the Redis queue connector.
     *
     * @param  \Illuminate\Queue\QueueManager  $manager
     * @return void
     */
    protected function registerRedisConnector($manager)
    {
        $manager->addConnector('redis', function () {
            return new RedisConnector($this->app['redis']);
        });
    }

    /**
     * Register the Beanstalkd queue connector.
     *
     * @param  \Illuminate\Queue\QueueManager  $manager
     * @return void
     */
    protected function registerBeanstalkdConnector($manager)
    {
        $manager->addConnector('beanstalkd', function () {
            return new BeanstalkdConnector;
        });
    }

    /**
     * Register the Amazon SQS queue connector.
     *
     * @param  \Illuminate\Queue\QueueManager  $manager
     * @return void
     */
    protected function registerSqsConnector($manager)
    {
        $manager->addConnector('sqs', function () {
            return new SqsConnector;
        });
    }
}
