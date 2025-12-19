<?php
session_start();
require_once 'config.php';

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
?> 