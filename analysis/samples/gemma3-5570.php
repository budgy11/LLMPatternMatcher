
</body>
</html>


function performSearch($searchTerm) {
  $dbHost = "localhost";
  $dbName = "your_database_name";
  $dbUser = "your_username";
  $dbPassword = "your_password";

  $mysqli = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

  if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
  }

  $sql = "SELECT * FROM your_table WHERE title LIKE ? OR description LIKE ?";
  $stmt = $mysqli->prepare($sql);

  if ($stmt->prepare() === false) {
    die("Error preparing statement: " . $mysqli->error);
  }

  $searchTermEscaped = "%" . $mysqli->real_escape_string($searchTerm) . "%"; //Important for LIKE queries

  $stmt->bind_param("ss", $searchTermEscaped); // "ss" means two strings

  $stmt->execute();

  $results = $stmt->get_result();

  $stmt->close();
  $results->close();

  return $results; //Returns a mysqli_result object, which you'll loop through.
}


<?php

// Assuming you have a database connection established (e.g., $db)

// Get the search query from the search bar
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$search_term = htmlspecialchars($search_term);

// Build the SQL query
$table_name = 'your_table_name'; // Replace with your actual table name
$column_to_search = 'your_column_name'; // Replace with the column to search

// Escape special characters for the SQL query (important for security - prevents SQL injection)
$search_term_escaped = mysqli_real_escape_string($db, $search_term);

// Build the SQL query
$query = "SELECT * FROM $table_name WHERE $column_to_search LIKE '%$search_term_escaped%'";
//  '%': Wildcard that means "any characters"
//  'LIKE': Allows for partial matches.
//  '%' :  Matches any string of characters before the search term.
//  '$search_term_escaped':  The value of the search term, escaped for security.

// Execute the query
$result = mysqli_query($db, $query);

// Check if the query was successful
if ($result) {
    // Display the search results
    echo '<form method="get" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">
            <input type="text" name="search" value="' . htmlspecialchars($search_term) . '" placeholder="Search...">
            <button type="submit">Search</button>
          </form>';

    echo '<table border="1">
            <thead>
                <tr>
                    <th>Column 1</th>
                    <th>Column 2</th>
                    <!-- Add more columns as needed -->
                </tr>
            </thead>
            <tbody>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        foreach ($row as $column => $value) {
            echo '<td>' . htmlspecialchars($value) . '</td>';  // Escape each value
        }
        echo '</tr>';
    }

    echo '</tbody>
        </table>';

} else {
    // Handle the error (e.g., display an error message)
    echo '<p>Error: ' . mysqli_error($db) . '</p>';
}


// Close the database connection (important!)
mysqli_close($db);
?>
