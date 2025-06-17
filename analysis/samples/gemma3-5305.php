

<?php

// Assuming you have a database connection established and you're using a database that supports SQL queries.

// Example using MySQLi (you can adapt this to other database drivers)

// 1. Get the search term from the form (using a form or a GET request)

// Example with a simple form:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $search_term = htmlspecialchars(trim($_POST["search"])); // Sanitize input!
} else {
  $search_term = "";
}


// 2.  Construct the SQL Query
$search_term = "%" . $search_term . "%"; //  '%' is the wildcard for 'like' operator.  Makes the search case-insensitive

$query = "SELECT * FROM products WHERE name LIKE '" . $search_term . "'"; // Assuming your table is named 'products' and has a column named 'name'

// 3. Execute the Query (using MySQLi)
$conn = new MySQLi("localhost", "your_db_user", "your_db_password", "your_db_name"); // Replace with your database credentials
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare($query); // Use prepared statements for security
if (!$stmt->execute()) {
    die("Query failed: " . $stmt->error);
}

$result = $stmt->get_result();

// 4. Display the Results

if ($result->num_rows > 0) {
  echo "<h2>Search Results for: " . htmlspecialchars($search_term) . "</h2>";
  echo "<table border='1'>";
  echo "<thead><tr><th>ID</th><th>Name</th><th>Description</th></tr></thead>";
  while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row["id"] . "</td>";
    echo "<td>" . $row["name"] . "</td>";
    echo "<td>" . $row["description"] . "</td>";
    echo "</tr>";
  }
  echo "</table>";

} else {
  echo "<p>No results found for: " . htmlspecialchars($search_term) . "</p>";
}

$stmt->close();
$conn->close();

?>
