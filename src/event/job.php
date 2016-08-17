<?php

use Cradle\CommandLine\Index as CLI;

return function($cwd, $args) {
    if(count($args) < 4) {
        CLI::error('Not enough arguments. Usage: `cradle package '
        . 'cblanquera/cradle-queue job random-mail '
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

    $this->preprocess(function($request, $response) use (&$args, &$data) {
        CLI::info($args[3] . ' is running');

        $request->setStage($data);

        $this->trigger($args[3], $request, $response);

        //if there was an error
        if($response->get('json', 'error')) {
            $error = $response->get('json', 'message');
            CLI::error('`'.$args[3].'` ' . $error, false);
            CLI::info(json_encode($data, JSON_PRETTY_PRINT));
            return;
        }

        CLI::success('`'.$args[3].'` job has been successfully executed.');
        CLI::info(json_encode($data));
    })
    //run CLI mode
    ->prepare();
};
