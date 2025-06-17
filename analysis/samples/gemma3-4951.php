    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent SQL injection
$searchTerm = htmlspecialchars($searchTerm);

// Escape the search term for the database query (important!)
$searchTerm = $conn->real_escape_string($searchTerm);


//  Example:  If you're searching through a table named 'products'
//  with a column named 'name'

// Simple search query (replace with your specific needs)
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = $conn->query($sql);

// Output the search results
?>
