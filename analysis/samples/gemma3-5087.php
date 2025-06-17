

<?php

// Assuming you have a database connection established (e.g., $db)

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (Important for security and to prevent XSS)
$searchTerm = htmlspecialchars(trim($searchTerm));

// Escape characters for database query (important for SQL injection prevention)
$searchTerm = $db->real_escape_string($searchTerm); // Assuming $db is your database object

//  Example query (adjust to your database structure and needs)
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = $db->query($sql);

// Check if the query was successful
if ($result) {
    // Display the results
    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . $row['name'] . " - " . $row['description'] . "</li>";
            // You can display other relevant fields here
        }
        echo "</ul>";
    } else {
        echo "No results found for '" . $searchTerm . "'.";
    }
} else {
    // Handle the error
    echo "Error executing query: " . $db->error;
}

?>
