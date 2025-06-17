
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db is a mysqli object)
// and you're retrieving data from a table called 'products' with a column 'name'

// Example database connection (replace with your actual connection)
$db = mysqli_connect("localhost", "username", "password", "database_name");

if (mysqli_connect_errno($db)) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}


// Check if the search form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the search term from the form
    $searchTerm = $_POST["search_term"];

    // Sanitize the search term (important for security)
    $searchTerm = mysqli_real_escape_string($db, $searchTerm);

    // Perform the search
    $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
    $result = mysqli_query($db, $sql);

    // Output the search results
    if (mysqli_num_rows($result) > 0) {
        echo "<h3>Search Results:</h3>";
        echo "<form method='post' action=''>";
        echo "<input type='text' name='search_term' value='" . htmlspecialchars($searchTerm) . "'><button type='submit'>Search</button>";
        echo "</form>";

        echo "<ul>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li>" . htmlspecialchars($row["name"]) . " - " . htmlspecialchars($row["description"]) . "</li>"; // Or display other relevant fields
        }
        echo "</ul>";

    } else {
        echo "<p>No results found for '" . htmlspecialchars($searchTerm) . "'.</p>";
    }
} else {
    // Display the search form
    echo "<h3>Search Products:</h3>";
    echo "<form method='post' action=''>";
    echo "<input type='text' name='search_term' placeholder='Enter search term'><button type='submit'>Search</button>";
    echo "</form>";
}

// Close the database connection (important!)
mysqli_close($db);
?>
