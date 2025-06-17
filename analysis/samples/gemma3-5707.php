
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db connection)

// Get the search term from the form (if submitted)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent SQL injection and XSS attacks
$searchTerm = htmlspecialchars(trim($searchTerm));

//  (Optional)  You can add more validation here, such as checking for empty search terms.
if (empty($searchTerm)) {
  $results = []; // Return an empty array if no search term is provided
} else {
  // Construct the SQL query
  //  *** IMPORTANT: This is a basic example.  In a real application, you MUST use prepared statements
  //  to prevent SQL injection.  I'm providing this for demonstration purposes.
  $tableName = 'your_table_name'; // Replace with your table name
  $columns = ['name', 'description']; // Replace with the columns you want to search

  $sql = "SELECT * FROM $tableName WHERE ";

  $conditions = [];
  foreach ($columns as $column) {
    $conditions[] = "$column LIKE '%$searchTerm%'";
  }

  $sql .= implode(' OR ', $conditions);

  // Execute the query
  $result = mysqli_query($db, $sql); // Replace $db with your database connection

  // Check for errors
  if (mysqli_error($db)) {
    $results = ['error' => mysqli_error($db)];
  } else {
    $results = $result;
  }
}

?>
