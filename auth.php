<?php
session_start();
$config = require 'config.php';

function is_logged_in() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

function check_login() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit;
    }
}