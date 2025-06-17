

<?php

// Assuming you have a database connection established and $db is your database connection object.

// Get the search term from the input field
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars(trim($searchTerm));

// Define the table name you want to search
$tableName = 'your_table_name';  // Replace with your actual table name

// Build the SQL query
$sql = "SELECT * FROM $tableName WHERE ";

// Add the WHERE clause based on whether the search term is empty or not
if (!empty($searchTerm)) {
    $sql .= "`name` LIKE '%" . $searchTerm . "%'"; // Replace 'name' with the actual column name you want to search
} else {
    // If the search term is empty, you might want to show all records or a message
    echo "<p>Enter a search term.</p>";
    exit; // Stop execution
}


// Execute the query
$result = mysqli_query($db, $sql);

// Check for errors
if (mysqli_errno($db)) {
    echo "Error running query: " . mysqli_error($db);
    exit;
}

// Display the results
echo "<form method='get' action=''>";
echo "<input type='text' name='search' value='" . $searchTerm . "'><input type='submit' value='Search'>";
echo "</form>";

echo "<h2>Search Results:</h2>";

if (mysqli_num_rows($result) > 0) {
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>" . $row['name'] . " - " . $row['description'] . "</li>"; // Adjust column names accordingly
        // Add other relevant fields here
    }
    echo "</ul>";
} else {
    echo "<p>No results found for '" . $searchTerm . "'.</p>";
}

// Close the database connection (important!)
mysqli_close($db);

?>
