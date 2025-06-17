

<?php

// Assuming you have a database connection established and a database table named 'products'
// with a column named 'name' for the product names

// Example database connection (replace with your actual details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if the search term is submitted
if (isset($_GET['search']) && !empty($_GET['search'])) {
  $searchTerm = $conn->real_escape_string($_GET['search']); // Sanitize the input to prevent SQL injection

  // Build the SQL query
  $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

  // Execute the query
  $result = $conn->query($sql);

  // Display the results
  if ($result->num_rows > 0) {
    echo "<form method='get' style='margin: 0;'>
          <input type='text' name='search' placeholder='Search products...' value='" . htmlspecialchars($searchTerm) . "'>
          <button type='submit'>Search</button>
          </form>";

    echo "<h3>Search Results:</h3>";
    while($row = $result->fetch_assoc()) {
      echo "<p>" . htmlspecialchars($row['name']) . "</p>";  // Escape to prevent XSS
    }
  } else {
    echo "<p>No products found matching your search term.</p>";
  }
} else {
  // Display the search bar if no search term is provided
  echo "<form method='get' style='margin: 0;'>
          <input type='text' name='search' placeholder='Search products...' value=''>
          <button type='submit'>Search</button>
          </form>";
}

// Close the connection
$conn->close();

?>
