
</body>
</html>


<?php
// ... (Database connection setup)

// Get the search term
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term
$searchTerm = htmlspecialchars(trim($searchTerm));

// Build the query using a prepared statement
$sql = "SELECT * FROM $tableName WHERE name LIKE ?";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind the parameter
$stmt->bind_param("s", $searchTerm);  // "s" indicates a string

// Execute the query
$result = $stmt->execute();

// Get the results
$result = $stmt->get_result();

// Output the results
if ($result->num_rows > 0) {
    echo "<ul>";
    while($row = $result->fetch_assoc()) {
        echo "<li>" . $row['name'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No results found.</p>";
}

// Close the statement
$stmt->close();
?>
