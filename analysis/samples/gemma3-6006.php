
</body>
</html>


<?php

// Assuming you have a database connection established and named $db

// Get the search term from the search bar (using GET or POST)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : ''; // Using GET method
// Alternatively, if you prefer POST:
// $searchTerm = isset($_POST['search']) ? $_POST['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars(trim($searchTerm));

//  You can add more sanitization here depending on your database and expected input
//  e.g., escaping special characters for SQL queries.

// *** Database Query ***

// Example: Searching in a table named 'products' with a column named 'name'
$query = "SELECT * FROM products WHERE name LIKE '%$searchTerm%'";

//  Adapt this query based on your table and column names,
//  and the desired search method (LIKE, LIKE '%...%', etc.)

// Execute the query
$result = mysqli_query($db, $query);  // Replace $db with your database connection

// Check if the query executed successfully
if ($result) {
    // Display the search results
    echo "<form method='get' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
    echo "<input type='text' name='search' value='" . htmlspecialchars($searchTerm) . "' placeholder='Search...'>";
    echo "<button type='submit'>Search</button>";
    echo "</form>";

    echo "<h2>Search Results:</h2>";

    if (mysqli_num_rows($result) > 0) {
        echo "<ul>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li>" . htmlspecialchars($row['name']) . " - " . htmlspecialchars($row['description']) . "</li>"; // Adjust column names
        }
        echo "</ul>";
    } else {
        echo "<p>No results found.</p>";
    }
} else {
    // Handle the error if the query failed
    echo "<p>Error executing query: " . mysqli_error($db) . "</p>";
}

// Close the database connection (important!)
mysqli_close($db);

?>
