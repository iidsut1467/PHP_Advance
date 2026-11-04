<?php

session_start();

require_once __DIR__ . '/config/database.php';

session_regenerate_id(true);
$_SESSION['user_id'] = (int)$user['id'];

?>
