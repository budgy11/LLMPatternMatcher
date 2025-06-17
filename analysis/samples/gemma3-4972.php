
<!-- HTML Form to Submit the Search Query -->
<form method="GET" action="">
  <input type="text" name="search" placeholder="Enter search term">
  <button type="submit">Search</button>
</form>


// Example of using prepared statements for search
if (isset($_GET['search']) && !empty($_GET['search'])) {
  $searchTerm = $_GET['search'];

  $sql = "SELECT * FROM your_table_name WHERE ";
  $params = [];

  // Dynamically build the WHERE clause and parameters
  foreach ($columns as $column) {
    $sql .= "($column) LIKE ?";
    $params[] = "%" . $searchTerm . "%";
  }

  $stmt = $conn->prepare($sql);
  $stmt->execute($params);
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // ... rest of the code to display the results
}


<?php

// Assuming you have a database connection established (e.g., mysqli)
// and you're using a simple database table named 'products' with a 'name' column.

// **Important:** Replace these with your actual database credentials and table/column names.

$db_host = "localhost";
$db_user = "your_db_user";
$db_pass = "your_db_password";
$db_name = "your_db_name";

// Attempt to connect to the database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get the search query from the user
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

// Sanitize the search term to prevent SQL injection
$search_term = $conn->real_escape_string($search_term); //Essential for security


// Build the SQL query (Example: simple substring search)
//  This is a basic example; adjust it based on your database design
//  and desired search functionality.
//  For more complex searches, consider using prepared statements with placeholders
//  to avoid SQL injection vulnerabilities and improve performance.
$sql = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'";


// Execute the query
$result = $conn->query($sql);


?>
