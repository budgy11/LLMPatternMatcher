    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// This is a basic example.  You'll likely want to adapt this to your
// specific database and search needs.

// -------------------------------------------------------------------
// Configuration
// -------------------------------------------------------------------

$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$results_per_page = 10; // Number of results to display per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// -------------------------------------------------------------------
// Database Connection (Replace with your actual connection)
// -------------------------------------------------------------------

// Example using a dummy database (replace with your database details)
// $db_host = "localhost";
// $db_name = "your_database_name";
// $db_user = "your_username";
// $db_pass = "your_password";
// $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }



// -------------------------------------------------------------------
// Search Logic
// -------------------------------------------------------------------

// Sanitize the search term
$search_term = trim($search_term);
$search_term = htmlspecialchars($search_term);  //Important for security - prevents XSS

// SQL query (adjust this based on your database schema)
$query = "SELECT * FROM your_table_name WHERE title LIKE '%" . $search_term . "%'"; // Example:  searching in a 'title' column.  Use proper escaping for your database system.


// Execute the query
$result = $conn->query($query);

// -------------------------------------------------------------------
// Pagination
// -------------------------------------------------------------------
$start = ($page - 1) * $results_per_page;
$end = $start + $results_per_page;


// -------------------------------------------------------------------
// Display Results
// -------------------------------------------------------------------

echo "<form method='get' action=''>";
echo "<input type='text' name='search' value='" . $search_term . "' style='width: 300px;'>";
echo "<input type='submit' value='Search'>";
echo "</form>";

if ($result->num_rows > 0) {
    echo "<h2>Search Results for: " . $search_term . "</h2>";
    echo "<table border='1'>";
    echo "<thead><tr><th>ID</th><th>Title</th><th>Description</th></tr></thead>";  // Adjust columns as needed
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";  // Adjust column names
        echo "<td>" . $row['title'] . "</td>";
        echo "<td>" . $row['description'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No results found for: " . $search_term . "</p>";
}

// Pagination links
if ($page > 1) {
    echo "<br><a href='?search=" . $search_term . "&page=" . ($page - 1) . "'>Previous</a>";
}

for ($i = 1; $i <= max(1, ceil($page / $results_per_page)); $i++) {
    if ($i != $page) {
        echo "<a href='?search=" . $search_term . "&page=" . $i . "'>$i</a> ";
    }
}

if ($page < max(1, ceil($page / $results_per_page))) {
    echo "<a href='?search=" . $search_term . "&page=" . ($page + 1) . "'>Next</a>";
}
// -------------------------------------------------------------------
// Close Connection
// -------------------------------------------------------------------
// $conn->close(); // Important to close the database connection

?>
