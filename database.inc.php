<?php
session_start();
// Check if the session ID needs to be updated
if (!isset($_SESSION['last_updated']) || $_SESSION['last_updated'] + 86400 < time()) {
    // Generate a new unique ID
    $newID = uniqid();

    // Update the session ID
    $_SESSION['ID'] = $newID;

    // Update the last updated timestamp
    $_SESSION['last_updated'] = time();
}

// Get the session ID
$session_id = $_SESSION['ID'];
$con=mysqli_connect('localhost','root','','chatbot');
?>