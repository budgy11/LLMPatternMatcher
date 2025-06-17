
</body>
</html>


<?php
// ... (database connection)

// Get the search term
$searchTerm = $_GET['search'];

// Prepare the statement
$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE name LIKE ?");

if ($stmt) {
    // Bind the parameter
    mysqli_stmt_bind_param($stmt, "s", "%" . $searchTerm . "%"); // "s" indicates a string parameter

    // Execute the query
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        // ... (Process the result like before)
    } else {
        echo "<p>Error executing prepared statement.</p>";
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    echo "<p>Error preparing statement.</p>";
}
?>
