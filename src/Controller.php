<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Framework\Queue;

use Cradle\Http\Request;
use Cradle\Http\Response;

use Cradle\Framework\App;
use Cradle\Framework\FlowTrait;

use Cradle\Framework\Queue\Action\Queue;

/**
 * Factory for model related flows
 *
 * @vendor   Cradle
 * @package  Framework
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Controller
{
    use FlowTrait;

    /**
     * Returns the action property
     * example `Flow::auth()->load()`
     * example `Flow::auth()->search()->load()`
     * example `Flow::auth()->search->load()`
     *
     * @param *string $name
     * @param *array  $args
     *
     * @return string
     */
    public function __call($name, $args)
    {
        $this->current = $this->actions['queue'];
        return $this->__callFlow($name, $args);
    }

    /**
     * Sets the app and model
     */
    public function __construct(App $app)
    {
        $this->actions['queue'] = $this->resolve(Queue::class, $app);
    }

    /**
     * Returns the action property
     * example `Flow::auth()->search->load`
     *
     * @param *string $name
     *
     * @return string|callable
     */
    public function __get($name)
    {
        return $this->__call($name, array());
    }
}
