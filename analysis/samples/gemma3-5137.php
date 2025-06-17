
</body>
</html>


<?php

// Assuming you have a database connection established and named $conn

// Get the search term from the search box
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security and preventing SQL injection)
$searchTerm = htmlspecialchars(trim($searchTerm));

// SQL query to search in a table (replace 'your_table' and 'your_column' with your actual table and column names)
$query = "SELECT * FROM your_table WHERE your_column LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = mysqli_query($conn, $query);

?>
