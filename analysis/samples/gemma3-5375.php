

<?php

// Assuming you have a database connection established and a table named 'products' with a 'name' column
// Example: $db = new PDO("mysql:host=localhost;dbname=your_database_name", "username", "password");

// Get the search term from the GET request
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars($searchTerm); // Prevents XSS

// You can add more sanitization here based on your data and requirements.
// For example, you might want to strip out unwanted characters or limit the length.


// Query the database
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";  // Use LIKE for partial matches
$stmt = $db->prepare($sql);
$stmt->execute();

// Fetch the results
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Display the search results
?>
