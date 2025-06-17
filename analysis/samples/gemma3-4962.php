

<?php

// Assuming you have a database connection established (e.g., mysqli)
// and a table named 'products' with a column named 'name' for searching.

// 1. Get the search term from the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = isset($_POST["search_term"]) ? trim($_POST["search_term"]) : "";
} else {
  $searchTerm = ""; // Initialize if no form submission yet
}

// 2. Sanitize and Escape the Search Term (IMPORTANT!)
// This prevents SQL injection vulnerabilities.
$searchTerm = mysqli_real_escape_string($connection, $searchTerm);  // Replace $connection with your database connection variable
$searchTerm = htmlspecialchars($searchTerm); // Escape for HTML output to prevent XSS

// 3. Perform the Search Query
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// 4. Execute the Query
$result = mysqli_query($connection, $sql);

// 5. Display the Results (Example - Adapt to your needs)
if (mysqli_num_rows($result) > 0) {
  echo "<h2>Search Results for: " . $searchTerm . "</h2>";
  echo "<table border='1'>";
  echo "<thead><tr><th>ID</th><th>Name</th><th>Price</th></tr></thead>";
  while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row["id"] . "</td>";
    echo "<td>" . $row["name"] . "</td>";
    echo "<td>" . $row["price"] . "</td>";
    echo "</tr>";
  }
  echo "</table>";
} else {
  echo "<p>No products found matching your search term.</p>";
}

?>
