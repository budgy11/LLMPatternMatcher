

<?php

// Assuming you have a form with an input field named 'search_term'

if (isset($_GET['search'])) {
  $searchTerm = $_GET['search'];

  // Sanitize the input to prevent XSS and SQL injection
  $searchTerm = htmlspecialchars(trim($searchTerm));

  // **Database Connection (Replace with your actual database credentials)**
  $servername = "localhost";
  $username = "your_username";
  $password = "your_password";
  $dbname = "your_database_name";

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit; // Stop execution if the database connection fails
  }

  // **Query Construction (Adjust based on your database schema)**
  $sql = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $searchTerm . "%'";

  // Execute the query
  $stmt = $conn->prepare($sql);
  $stmt->execute();

  // Fetch the results
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // **Display Results (Customize based on your needs)**
  if (count($results) > 0) {
    echo "<h2>Search Results for: " . $searchTerm . "</h2>";
    echo "<table border='1'>";
    echo "<thead><tr><th>ID</th><th>Column1</th><th>Column2</th></tr></thead>";  // Customize header based on your columns
    echo "<tbody>";
    foreach ($results as $row) {
      echo "<tr>";
      echo "<td>" . $row['id'] . "</td>"; // Assuming 'id' is your primary key
      echo "<td>" . $row['column1'] . "</td>"; // Replace with your actual column names
      echo "<td>" . $row['column2'] . "</td>";
      echo "</tr>";
    }
    echo "</tbody></table>";
  } else {
    echo "<p>No results found for '" . $searchTerm . "'.</p>";
  }

} else {
  // If no search term is provided, display a placeholder or a simple search box.
  echo "<form method='get' action=''>"; // Adjust 'action' to the correct URL
  echo "<input type='text' name='search' placeholder='Enter search term...'>";
  echo "<button type='submit'>Search</button>";
  echo "</form>";
}

// Close the database connection (important!)
$conn = null;
?>
