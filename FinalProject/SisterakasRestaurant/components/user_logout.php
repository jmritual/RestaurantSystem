<?php

// Include the file that contains the database connection
include 'connect.php';

// Start the session
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect the user to the home page after logout
header('location:../home.php');

?>