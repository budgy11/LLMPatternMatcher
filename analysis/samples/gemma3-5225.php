
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  // Check if the search term is present in the query string
  if (isset($_GET["search"]) && !empty($_GET["search"])) {
    $searchTerm = $_GET["search"];

    // Perform the database query
    $query = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $searchTerm . "%'";  // Replace your_table_name and your_column_name
    $result = mysqli_query($conn, $query);

    if ($result) {
      // Display the results
      echo "<h2>Search Results for: " . $searchTerm . "</h2>";
      echo "<table border='1'>";
      echo "<thead><tr><th>ID</th><th>Column1</th><th>Column2</th></tr></thead>";
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>"; // Adjust column names
        echo "<td>" . $row["column1"] . "</td>"; // Adjust column names
        echo "<td>" . $row["column2"] . "</td>"; // Adjust column names
        echo "</tr>";
      }
      echo "</table>";
    } else {
      echo "<p>Error executing query.</p>";
    }
  } else {
    echo "<form method='GET'>";
    echo "<input type='text' name='search' placeholder='Enter search term'>";
    echo "<button type='submit'>Search</button>";
    echo "</form>";
  }
} else {
  // Handle other request methods (optional)
  echo "Invalid request method.";
}

?>
