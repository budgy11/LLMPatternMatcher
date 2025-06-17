
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      width: 300px;
      margin: 0 auto;
      text-align: center;
    }

    input[type="text"] {
      width: 80%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("search-input").value;

      // Perform your search logic here.  For example,
      // you could filter a list of items based on the search term.

      // Example:
      if (searchTerm.length > 0) {
        alert("You searched for: " + searchTerm);
        // Replace this alert with your actual search implementation
      } else {
        alert("Please enter a search term.");
      }
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search term from the form (e.g., using $_GET or $_POST)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent SQL injection
$searchTerm = $searchTerm; // Basic sanitization - more robust sanitization is recommended

// Build the SQL query
$tableName = 'your_table_name'; // Replace with your table name
$columnName = 'your_column_name'; // Replace with the column you want to search in

$sql = "SELECT * FROM $tableName WHERE $columnName LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = mysqli_query($conn, $sql);

// Check for errors in the query
if (mysqli_error($conn)) {
    echo "Error executing query: " . mysqli_error($conn);
    exit;
}

// Display the search results (adjust this section as needed)
if (mysqli_num_rows($result) > 0) {
    echo "<h2>Search Results for: " . $searchTerm . "</h2>";
    echo "<table border='1'>";
    echo "<thead><tr><th>Column 1</th><th>Column 2</th><th>Column 3</th></tr></thead>"; // Adjust column names as needed
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . htmlspecialchars($value) . "</td>"; // Important: Use htmlspecialchars() for security
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No results found for: " . $searchTerm . "</p>";
}


// Example HTML form for the search bar
echo "<form method='GET' action='your_script.php'>  <!-- Replace 'your_script.php' with the actual script filename -->
    <label for='search'>Search:</label>
    <input type='text' id='search' name='search' value='" . htmlspecialchars($searchTerm) . "'>
    <button type='submit'>Search</button>
</form>";
?>
