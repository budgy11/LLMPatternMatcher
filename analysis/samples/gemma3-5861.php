

<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize and prepare the search term (important for security)
$searchTerm = htmlspecialchars($searchTerm);
$searchTerm = trim($searchTerm);

// Parameterized query (prepared statement) - MUCH more secure
$sql = "SELECT * FROM your_table WHERE title LIKE ?"; // Question mark is a placeholder

$stmt = mysqli_stmt_prepare($conn, $sql);  // Prepare the statement

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $searchTerm);  // Bind the search term
    mysqli_stmt_execute($stmt);

    // Get the results
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        echo "<form method='get' action=''>";
        echo "<input type='text' name='search' value='" . $searchTerm . "' placeholder='Search...'>";
        echo "<button type='submit'>Search</button>";
        echo "</form>";

        echo "<h2>Search Results:</h2>";
        echo "<table border='1'>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['title'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Error executing query.</p>";
    }

    mysqli_stmt_close($stmt); // Close the statement
} else {
    echo "<p>Error preparing statement.</p>";
}

mysqli_close($conn);

?>
