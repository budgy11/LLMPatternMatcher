

<?php

// Assuming you have a database connection established (e.g., $conn)
// and you're looking for data based on user input.

// 1. Get the search term from the user (from the search bar)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : ''; // Get from GET request, default to empty string

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars(trim($searchTerm));

// 2. Prepare the SQL query
//    - Use prepared statements to prevent SQL injection attacks!
$sql = "SELECT * FROM your_table_name WHERE your_column_name LIKE ?"; // Replace your_table_name and your_column_name

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind the search term to the parameter
$stmt->bind_param("s", $searchTerm); // "s" indicates a string parameter

// 3. Execute the query
$stmt->execute();

// 4. Fetch the results
$results = $stmt->get_result();

// 5. Display the results

if ($results->num_rows > 0) {
    echo "<form method='get' action='your_script.php'>  <!-- Replace your_script.php -->
        <input type='text' name='search' placeholder='Search...'>";
    echo "<button type='submit'>Search</button>
        </form>";

    echo "<ul>";
    while ($row = $results->fetch_assoc()) {
        echo "<li>" . $row['your_column_name'] . "</li>"; // Replace your_column_name
    }
    echo "</ul>";

} else {
    echo "<p>No results found for '" . $searchTerm . "'.</p>";
}

// 6. Close the statement and connection (important!)
$stmt->close();
$conn->close();

?>
