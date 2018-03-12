> Deprecation Notice: This project has been moved to https://github.com/CradlePHP/cradle-queue

# cradle-queue
RabbitMQ with Fork and Exec workers. Built for the [Cradle Framework](https://cradlephp.github.io/)

## 1. Requirements

You should be using CradlePHP currently at `dev-master`. See
[https://cradlephp.github.io/](https://cradlephp.github.io/) for more information.

## 2. Install

```
composer require cblanquera/cradle-queue
```

Then in `/bootstrap.php`, add

```
->register('cblanquera/cradle-queue')
```

## 3. Setup

Open `/config/services.php` and add

```
'rabbitmq-main' => [
    'host' => '127.0.0.1',
    'port' => 5672,
    'user' => 'guest',
    'pass' => 'guest'
],
```

## 4. Methods

```
cradle('global')->queue(*string $event, array $data);
```

An easy way to queue.

```
cradle('global')
    ->queue()
    ->setData(*array $data)
    ->setDelay(*string $delay)
    ->setPriority(*int $priority)
    ->setQueue(*string $queueName)
    ->setRetry(*int $retry)
    ->send(*string $task, bool $duplicates = true);
```

Returns the queue class for advance manipulation. If you want to prevent
duplicates from entering your queue, set the `$duplicates` flag to false and
turn on Redis (this is the only way I can figure this can happen).
