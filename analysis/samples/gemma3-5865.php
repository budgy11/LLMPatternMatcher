
</body>
</html>


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


<?php

// Assuming you have a database connection established and named $conn

// Get the search term from the GET request
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars(trim($searchTerm)); 

//  Optional: Validate the search term (e.g., character length, allowed characters)

// Example:  Restrict to alphanumeric characters
// $searchTerm = preg_replace('/[^a-zA-Z0-9\s]/', '', $searchTerm);


// Perform the search (replace this with your actual database query)
$results = searchDatabase($searchTerm);  // Calling a function to handle the query

// Display the search bar and the results
?>
