
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db connection)

// Get the search term from the query string (e.g., from a GET request)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term - important for security and preventing SQL injection
$searchTerm = htmlspecialchars(trim($searchTerm));

//  Example:  Let's assume you're searching a table named 'products'
//  and you want to search the 'name' and 'description' columns.

//  If you have a more complex query, you'll replace this with your actual SQL.

$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%' OR description LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = mysqli_query($db, $sql);  // Replace $db with your actual database connection

// Check if the query was successful
if ($result) {
    echo "<form method='get' action=''>";
    echo "<input type='text' name='search' value='" . $searchTerm . "' placeholder='Search...'>";
    echo "<button type='submit'>Search</button>";
    echo "</form>";

    // Display the search results
    if (mysqli_num_rows($result) > 0) {
        echo "<ul>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li>" . $row['name'] . " - " . $row['description'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No results found for '" . $searchTerm . "'</p>";
    }

} else {
    // Handle the error (e.g., log the error)
    echo "<p>Error executing query: " . mysqli_error($db) . "</p>";
}

mysqli_close($db); // Close the database connection
?>
