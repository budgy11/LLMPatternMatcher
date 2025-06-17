

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin: 20px;
    }
    input[type="text"] {
      padding: 8px;
      width: 300px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box; /* Ensures padding doesn't add to width */
    }
    button {
      padding: 8px 12px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .results {
      margin-top: 20px;
      list-style: none;
      padding: 0;
    }
    .results li {
      margin-bottom: 10px;
      padding: 10px;
      border: 1px solid #eee;
      background-color: #f9f9f9;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <h1>Simple Search</h1>
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <div id="results" class="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value.toLowerCase();
      var resultsDiv = document.getElementById("results");

      // Example data - replace this with your actual data source
      var data = [
        { title: "Apple iPhone 13", description: "Latest iPhone model." },
        { title: "Samsung Galaxy S22", description: "Premium Android phone." },
        { title: "Google Pixel 6", description: "Best camera phone." },
        { title: "Laptop - Dell XPS 13", description: "Powerful laptop for work." },
        { title: "Keyboard - Logitech MX Keys", description: "Ergonomic wireless keyboard." }
      ];

      // Clear previous results
      resultsDiv.innerHTML = "";

      // Filter data based on search term
      var filteredData = data.filter(function(item) {
        return item.title.toLowerCase().includes(searchTerm) || item.description.toLowerCase().includes(searchTerm);
      });

      // Display results
      if (filteredData.length > 0) {
        filteredData.forEach(function(item) {
          var listItem = document.createElement("li");
          listItem.innerHTML = "<b>" + item.title + "</b><br>" + item.description;
          resultsDiv.appendChild(listItem);
        });
      } else {
        var noResults = document.createElement("p");
        noResults.innerHTML = "No results found.";
        resultsDiv.appendChild(noResults);
      }
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db is your PDO connection)
// and you're working with a database table named 'products' (adjust as needed)

// 1. Get the Search Term
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// 2. Sanitize the Search Term (Crucial for Security)
$searchTerm = htmlspecialchars($searchTerm); // Prevents XSS attacks
//  Consider adding further sanitization if needed, depending on your database and input handling.

// 3. Build the SQL Query
$tableName = 'products'; // Replace with your table name
$columnName = 'name';   // Replace with the column you want to search in
$sql = "SELECT * FROM $tableName WHERE $columnName LIKE '%$searchTerm%'";

// 4. Execute the Query
try {
  $stmt = $db->prepare($sql);
  $stmt->execute();

  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Fetch results as an associative array

  // 5. Display the Results (Example - Adapt to your needs)
  if ($results) {
    echo "<form action='search.php' method='get'>";
    echo "<input type='text' name='search' value='$searchTerm'>";
    echo "<button type='submit'>Search</button>";
    echo "</form>";

    echo "<h2>Search Results:</h2>";
    echo "<table border='1'>";
    echo "<thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Price</th></tr></thead>";
    echo "<tbody>";
    foreach ($results as $row) {
      echo "<tr>";
      echo "<td>" . $row['id'] . "</td>";
      echo "<td>" . $row['name'] . "</td>";
      echo "<td>" . $row['description'] . "</td>";
      echo "<td>" . $row['price'] . "</td>";
      echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
  } else {
    echo "<p>No results found for '$searchTerm'.</p>";
  }

} catch (PDOException $e) {
  echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}

?>
