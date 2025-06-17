  <input type="text" name="search" value="<?php echo $searchTerm; ?>" />
  <input type="submit" value="Search" />
</form>

<?php

// Assume a $conn mysqli connection variable exists.
// If not, you must establish a connection using mysqli_connect().

//  Example Connection (replace with your actual details)
// $conn = mysqli_connect("localhost", "username", "password", "database_name");

//  Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';  // Get the search term

// Sanitize the search term - Important for security!
$searchTerm = htmlspecialchars(trim($searchTerm));

// Table name to search in (replace with your actual table name)
$tableName = 'your_table_name';

// Column to search in (replace with the column you want to search)
$searchColumn = 'your_column_name';

// Prepare the SQL query
$query = "SELECT * FROM $tableName WHERE $searchColumn LIKE '%$searchTerm%'";

// Execute the query
// $result = mysqli_query($conn, $query);

// Assume a $conn mysqli connection variable exists.
// If not, you must establish a connection using mysqli_connect().

// Prepare the statement
// $stmt = mysqli_prepare($conn, "SELECT * FROM $tableName WHERE $searchColumn LIKE ?");

// Bind the parameter (the search term)
// mysqli_stmt_bind_param($stmt, "s", $searchTerm);

// Execute the statement
// mysqli_stmt_execute($stmt);

// Get the results
// $result = mysqli_stmt_get_result($stmt); // or mysqli_stmt_use_result($stmt);


// Example with hardcoded data (replace with your table structure)
$data = array(
    array('id' => 1, 'name' => 'Apple', 'description' => 'A delicious fruit'),
    array('id' => 2, 'name' => 'Banana', 'description' => 'A yellow fruit'),
    array('id' => 3, 'name' => 'Orange', 'description' => 'A citrus fruit'),
);

// Output the search results
if ($searchTerm) {
  echo "<h3>Search Results for: " . htmlspecialchars($searchTerm) . "</h3>";
  echo "<ul>";
  foreach ($data as $row) {
    if (strpos(strtolower($row['name']), strtolower($searchTerm)) !== false ||
        strpos(strtolower($row['description']), strtolower($searchTerm)) !== false) {
      echo "<li>ID: " . $row['id'] . ", Name: " . $row['name'] . ", Description: " . $row['description'] . "</li>";
    }
  }
  echo "</ul>";
} else {
  echo "<p>Enter a search term above.</p>";
}



// Close the result set
// mysqli_free_result($result);

// Close the statement
// mysqli_close($stmt);

?>
