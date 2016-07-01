<?php
require_once 'user.php';
require_once 'StudentCollection.php';

// make database connection or show error
$link =  mysqli_connect('localhost', 'root', '', 'student');
if (!$link) {
    die("Connection error: " . mysqli_connect_error());
}

