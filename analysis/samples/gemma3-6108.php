

<?php

// Assuming you have a database connection established
// (e.g., mysqli, PDO) - Replace with your actual connection details
// For this example, we'll use a simplified placeholder:
// $db = new mysqli("localhost", "username", "password", "database_name");
// if ($db->connect_error) {
//     die("Connection failed: " . $db->connect_error);
// }


// Function to handle the search
function performSearch($search_term, $table_name, $search_columns = null) {
  // $db is assumed to be your database connection object.

  $sql = "SELECT * FROM " . $table_name . " WHERE ";

  if ($search_columns) {
    $conditions = [];
    foreach ($search_columns as $column) {
      $conditions[] = "`" . $column . "`" . " LIKE '%" . $search_term . "%'";
    }
    $sql .= implode(" OR ", $conditions);
  } else {
    // Search across all columns.  Be cautious about this in a production environment
    // as it can be very slow and vulnerable to SQL injection.
    $columns = array_keys($db->query("SHOW COLUMNS FROM " . $table_name));
    $conditions = [];
    foreach ($columns as $column) {
        $conditions[] = "`" . $column . "`" . " LIKE '%" . $search_term . "%'";
    }
    $sql .= implode(" OR ", $conditions);
  }


  $result = $db->query($sql);

  if ($result->num_rows > 0) {
    return $result; // Return the result set
  } else {
    return false; // No results found
  }
}



// ---  Example Usage  ---

// Placeholder for your database connection
// $db = new mysqli("localhost", "username", "password", "database_name");

// Example table name
$table_name = "products"; // Replace with your actual table name

// Example search term
$search_term = "laptop";

// You can optionally specify the columns to search within
// $search_columns = ["name", "description"];

// Perform the search
$results = performSearch($search_term, $table_name);

// Display the results
if ($results) {
  echo "<form action=\"search.php\" method=\"get\">
        <input type=\"text\" name=\"search\" placeholder=\"Enter search term\" value=\"" . htmlspecialchars($search_term) . "\">
        <input type=\"submit\" value=\"Search\">
      </form>";

  echo "<br><br>Search Results:<br>";
  if ($results->num_rows > 0) {
    while ($row = $results->fetch_assoc()) {
      echo "Product Name: " . htmlspecialchars($row["name"]) . "<br>";
      echo "Description: " . htmlspecialchars($row["description"]) . "<br>";
      echo "<br>";
    }
  } else {
    echo "No results found for '" . htmlspecialchars($search_term) . "'.";
  }

} else {
  echo "Search failed. Check your database connection and table name.";
}

// Close the database connection (important!)
// $db->close();
?>
