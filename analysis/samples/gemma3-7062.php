

<?php
// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_user');
define('DB_PASSWORD', 'your_db_password');
define('DB_NAME', 'your_db_name');

// Set error reporting to display errors (for development only)
error_reporting(E_ALL);
ini_set('display_errors', 1);


<?php

// Database connection function
function connect_to_db() {
    $db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($db->connect_error) {
        die('Connection failed: ' . $db->connect_error);
    }

    return $db;
}

// Function to execute a query
function execute_query($db, $query) {
    $result = $db->query($query);
    if (!$result) {
        die('Query failed: ' . $db->error);
    }
    return $result;
}
?>
