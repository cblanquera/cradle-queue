<?php

return function($cwd) {
    copy(__DIR__ . '/../worker.php', $cwd . '/worker.php');
};
