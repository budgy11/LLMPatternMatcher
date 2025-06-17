
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)
// and a table named 'products' with a 'name' column.

// 1. Get the search term from the form
if (isset($_GET['search']) && !empty($_GET['search'])) {
  $searchTerm = $_GET['search'];
} else {
  $searchTerm = ''; // Reset search term if not provided
}

// 2. Prepare the SQL query
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// 3. Execute the query
$result = mysqli_query($conn, $sql);

?>
