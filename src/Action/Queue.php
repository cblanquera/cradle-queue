<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Framework\Queue\Action;

use Cradle\Http\Request;
use Cradle\Http\Response;
use Cradle\Framework\App;

/**
 * Typical model create action steps
 *
 * @vendor   Cradle
 * @package  Framework
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Queue
{
    /**
     * @var App $app
     */
    protected $app = null;

    /**
     * Preps the Action binding the model given
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Sends the queue task
     *
     * @param Request  $request
     * @param Response $response
     * @param *string  $event
     * @param string   $priority
     *
     * @return Controller
     */
    public function send(
        Request $request,
        Response $response,
        $event,
        $priority = 'low'
    ) {
        $this->app->package('global')->queue($event, $request->getStage(), $priority);
        return $this;
    }
}
