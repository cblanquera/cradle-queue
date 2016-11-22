<?php //-->

use Cradle\Framework\Flow;
use Cradle\Framework\Queue\Controller;
use PhpAmqpLib\Message\AMQPMessage;

Flow::register('queue', function() use ($cradle) {
    static $cache = null;

    if(is_null($cache)) {
        $cache = new Controller($cradle);
    }

    return $cache;
});

//cli events
$cradle
    ->on('cblanquera-cradle-queue-install', include(__DIR__ . '/src/event/install.php'))
    ->on('cblanquera-cradle-queue-uninstall', include(__DIR__ . '/src/event/uninstall.php'))
    ->on('cblanquera-cradle-queue-queue', include(__DIR__ . '/src/event/queue.php'))
    ->on('cblanquera-cradle-queue-job', include(__DIR__ . '/src/event/job.php'));

//shortcuts
$cradle->on('Rabbit Queue %s', function($request, $response) {
    $meta = $this->getEventHandler()->getMeta();
    $callback = Flow::queue()->send($meta['variables'][0]);

    call_user_func($callback, $request, $response);
});

/**
 * Queue capabilities
 * see: https://github.com/php-amqplib/php-amqplib
 * which is the official PHP library from
 * https://www.rabbitmq.com/tutorials/tutorial-one-php.html
 *
 * @param string $task The task name
 * @param array  $data Data to use for this task
 *
 */
$cradle->package('global')->addMethod('queue', function(
    $task = null,
    $data = array(),
    $priority = 'low'
)
use ($cradle)
{
    static $channel = null;

    //get the channel
    if(is_null($channel)) {
        $service = $cradle->package('global')->service('queue-main');

        if(!$service) {
            return false;
        }

        try {
            $channel = $service->channel();
        } catch(Throwable $e) {
            return false;
        } catch(Exception $e) {
            return false;
        }
    }

    //get the queue name
    $settings = $cradle->package('global')->config('settings');

    $name = 'queue';

    if(isset($settings['queue']) && trim($settings['queue'])) {
        $name = $settings['queue'];
    }

    //set the task
    $data['__TASK__'] = $task;

    //declare the queue
    $channel->queue_declare(
        $name,
        false,
        true,
        false,
        false,
        false,
        array(
            'x-max-priority' => array('I', 10)
        )
    );

     // set message
    $message = new AMQPMessage(
        json_encode($data),
        array(
            'priority' => $priority,
            'delivery_mode' => 2
        )
    );

    $channel->exchange_declare($name.'-xchnge', 'direct');
    $channel->queue_bind($name, $name.'-xchnge');

    // queue it up main queue container
    $channel->basic_publish($message, $name.'-xchnge');

    return $this;
});
