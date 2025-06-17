

<?php

// Assuming you have a database connection established (e.g., $db)
// You'll need a database connection object here.  This is a placeholder.

// Example database connection (replace with your actual connection)
$db = mysqli_connect("localhost", "your_username", "your_password", "your_database");

if (mysqli_connect_errno($db)) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error() . "
";
  exit; // Stop script execution if connection fails
}


// Get the search term from the GET request
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$search_term = trim($search_term); // Remove leading/trailing whitespace


// Sanitize the search term (important for security)
$search_term = mysqli_real_escape_string($db, $search_term);


//  Construct the SQL query
$query = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $search_term . "%'"; // Replace 'your_table_name' and 'your_column_name'


// Execute the query
$result = mysqli_query($db, $query);


?>
