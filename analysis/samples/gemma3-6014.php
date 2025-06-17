
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., using mysqli)
// Let's call it $conn

// Get the search query from the form
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the input to prevent SQL injection
$search_query = htmlspecialchars(trim($search_query));

// ---  Database Query ---

// This is a basic example. Adapt this query to your database schema and needs.
// Replace 'table_name' with the actual name of your table.
// Replace 'column_name' with the column you want to search in.
//  Also replace the database connection details.


$sql = "SELECT * FROM table_name WHERE column_name LIKE '%" . $search_query . "%'";

// Execute the query
$result = mysqli_query($conn, $sql); 

// ---  Output the Results ---

if (mysqli_num_rows($result) > 0) {
    echo "<form method='get' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>
          <input type='text' name='search' value='" . htmlspecialchars($search_query) . "' placeholder='Search...'>
          <button type='submit'>Search</button>
        </form>";

    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>" . htmlspecialchars($row['column_name']) . "</li>"; // Adjust this line to display the appropriate columns
    }
    echo "</ul>";
} else {
    echo "<p>No results found.</p>";
}

// Close the database connection (important!)
mysqli_close($conn);

?>
