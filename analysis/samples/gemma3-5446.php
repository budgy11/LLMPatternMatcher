
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., using mysqli or PDO)
// Replace these with your actual connection details
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search query from the form
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important - even though prepared statements largely handle this)
$search_term = $conn->real_escape_string($search_term);

// SQL query for searching using prepared statements
$sql = "SELECT * FROM your_table_name WHERE your_column_name LIKE ?";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind the parameter
$stmt->bind_param("s", $search_term); // "s" indicates a string parameter

// Execute the query
$result = $stmt->execute();

// Fetch the results
if ($result) {
    while ($row = $stmt->fetch_assoc()) {
        echo "<li>";
        echo "<strong>" . htmlspecialchars($row["your_column_name_1"]) . "</strong>: " . htmlspecialchars($row["your_column_name_2"]) . "<br>";
        echo "</li>";
    }
} else {
    echo "<p>Error executing query.</p>";
}

$stmt->close();
$conn->close();
?>
