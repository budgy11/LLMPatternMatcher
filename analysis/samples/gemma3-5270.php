
  <script>
    // Optional: Add some basic styling for the search bar
    document.getElementById('search_term').placeholder = 'Search for products, articles, etc.';
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search term from the input field
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent SQL injection
$searchTerm = htmlspecialchars(trim($searchTerm));

// SQL query construction
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'"; // Replace 'products' with your table name and 'name' with the column to search
// OR, if you want an exact match:
// $sql = "SELECT * FROM products WHERE name = '" . $searchTerm . "'";


// Execute the query
$result = mysqli_query($conn, $sql);

?>
