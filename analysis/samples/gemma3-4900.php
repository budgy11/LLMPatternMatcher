
<!DOCTYPE html>
<html>
<head>
    <title>Search Example</title>
</head>
<body>

<h1>Search Products</h1>

<form method="GET" action="">
    <input type="text" name="search" placeholder="Enter search term...">
    <button type="submit">Search</button>
</form>

</body>
</html>


$search_query = $_GET['search'];

// Prepare the statement
$stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ?");

// Bind the parameter
$stmt->bind_param("s", "%" . $search_query . "%"); // "s" indicates a string parameter

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Output results
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<li>" . $row["name"] . " - " . $row["description"] . "</li>";
    }
}


<?php

// Assuming you have a database connection established (e.g., $db)

// Get the search term from the user input
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (very important for security)
$search_term = htmlspecialchars(trim($search_term));

// Example: Search in a 'products' table
// (Replace 'products', 'name', and 'description' with your actual table and column names)

// SQL query (using prepared statements - BEST PRACTICE!)
$sql = "SELECT * FROM products WHERE name LIKE ? OR description LIKE ?";

// Prepare the statement
$stmt = $db->prepare($sql);

// Bind the parameters
$stmt->bind_param("s", "%" . $search_term . "%"); // 's' for string, '%' for wildcard
$stmt->bind_param("s", "%" . $search_term . "%");

// Execute the query
$stmt->execute();

// Get the results
$results = $stmt->get_result();

// Output the search results
if ($results->num_rows > 0) {
  echo "<h2>Search Results for: " . htmlspecialchars($search_term) . "</h2>";
  echo "<table border='1'>";
  echo "<tr><th>Name</th><th>Description</th></tr>";

  while ($row = $results->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
    echo "</tr>";
  }

  echo "</table>";
} else {
  echo "<p>No results found for: " . htmlspecialchars($search_term) . "</p>";
}

// Close the statement
$stmt->close();

?>
