<?php
// Login app
define('APP_USER', 'dédé');
define('APP_PW_HASH', '$2b$12$jblQUY5WAXkVSgihb5oONuq6TJnRn3aVbYTpXmXTcAVqZhLQhGLj2');


define('SESSION_TIMEOUT', 900);
if (session_status() === PHP_SESSION_NONE) session_start();
?>