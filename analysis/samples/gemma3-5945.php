

// Using prepared statements (the best practice for security)
$stmt = $db->prepare("SELECT * FROM products WHERE name LIKE ?");
$stmt->bind_param("s", "%" . $search_term . "%"); // 's' indicates a string
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();


<?php

// Assuming you have a database connection established (e.g., using mysqli)
// Replace these with your actual connection details
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle the search query
function searchDatabase($search_term, $db_host, $db_name, $db_user, $db_password) {
  // Establish database connection
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Sanitize the search term to prevent SQL injection
  $search_term = $conn->real_escape_string(trim($search_term)); 

  // Build the SQL query
  $query = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $search_term . "%'"; 
  // Replace 'your_table_name' and 'your_column_name' with your actual table and column names.
  // The '%' wildcards allow for partial matches.

  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // Output the results
    echo "<form method='get' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
          <input type='text' name='search' value='" . htmlspecialchars($search_term) . "' placeholder='Search...'>
          <button type='submit'>Search</button>
          </form>";

    echo "<br>";
    echo "<ul>";
    // Output each row of the result
    while ($row = $result->fetch_assoc()) {
      echo "<li>" . htmlspecialchars($row["your_column_name"]) . "</li>"; // Display the data from your column
    }
    echo "</ul>";
  } else {
    echo "<p>No results found for '" . htmlspecialchars($search_term) . "'.</p>";
  }

  $conn->close();
}

// Call the function to execute the search
searchDatabase("", $db_host, $db_name, $db_user, $db_password); //  Empty string as initial search term

?>
