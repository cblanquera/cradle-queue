<?php

return function($cwd) {
    unlink($cwd . '/worker.php');
};
