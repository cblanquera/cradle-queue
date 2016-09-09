<?php

use Cradle\CommandLine\Index as CLI;

return function($cwd, $args) {
    if(count($args) < 4) {
        CLI::error('Not enough arguments. Usage: `cradle package '
        . 'cblanquera/cradle-queue queue random-mail '
        . '"?subject=hi&body=hello..."', true);
    }

    $data = array();
    if(isset($args[4])) {
        if(strpos($args[4], '?') === 0) {
            parse_str(substr($args[4], 1), $data);
        } else {
            $data = json_decode($args[4], true);
        }
    }

    //run preprocesses
    $this->prepare();

    //now queue
    $this
        ->package('global')
        ->queue($args[3], $data);

    CLI::success('`'.$args[3].'` has been successfully queued.');
};
