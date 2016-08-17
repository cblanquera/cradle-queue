# cradle-mail
Mail Handling for Cradle with Swift Mailer

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
use PhpAmqpLib\Connection\AMQPLazyConnection;
```

in the next line right after the `<?php`. Then add the following to the array.

```
'queue-main' => new AMQPLazyConnection('127.0.0.1', 5672, 'guest', 'guest'),
```

## 4. Recipes

Once the database is installed open up `/public/index.php` and add the following.

```
<?php

use Cradle\Framework\Flow;

return cradle()
    //add routes here
    ->get('/queue', 'Queue Pseudo Mail')

    //add flows here
    ->flow(
        'Queue Pseudo Mail',
        Flow::queue()->send('Send Mail')
    );
```

You can also express the queue step as a string like the following example.

```
->flow(
    'Queue Pseudo Mail',
    'Rabbit Queue Send Mail'
)
```

Then open up `/bootstrap.php` and add the following.

```
->flow('Send Mail', function($request, $response) {
        echo 'Sending Mail';
})
```

## 5. CLI

To see this in action you need to open up two terminals. The first one will be
your RabbitMQ server. If your RabbitMQ server is not on you can use `$ rabbitmq-server`
**Assuming that RabbitMQ server is installed**.

With the other terminal, go to your project directory and run `php worker.php`

Then go to your browser and visit `http://localhost/queue`. You should see the worker
get updated and echoing `Sending Mail`.
