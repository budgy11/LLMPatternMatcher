
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize and prepare the search term (important for security)
$searchTerm = htmlspecialchars($searchTerm); // Escape HTML entities
$searchTerm = trim($searchTerm); // Remove leading/trailing whitespace

// Basic search logic (replace with your database query)
if ($searchTerm) {
    //  Example:  Simple LIKE query - adjust to your database structure
    $sql = "SELECT * FROM your_table WHERE title LIKE '%" . $searchTerm . "%'";  // Replace 'your_table' with your table name
    $result = mysqli_query($conn, $sql);  // Replace $conn with your database connection

    if ($result) {
        // Display the search results
        echo "<form method='get' action=''>";
        echo "<input type='text' name='search' value='" . $searchTerm . "' placeholder='Search...'>";
        echo "<button type='submit'>Search</button>";
        echo "</form>";

        echo "<h2>Search Results:</h2>";
        echo "<table border='1'>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['title'] . "</td>"; // Adjust column names as needed
            echo "</tr>";
        }
        echo "</table>";

    } else {
        echo "<p>Error executing query.</p>";
    }
} else {
    // Display the search form if no search term is provided
    echo "<form method='get' action=''>";
    echo "<input type='text' name='search' placeholder='Search...'>";
    echo "<button type='submit'>Search</button>";
    echo "</form>";
}

mysqli_close($conn); // Close the database connection

?>
