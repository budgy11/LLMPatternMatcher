

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin-bottom: 10px;
    }

    input[type="text"] {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 300px;
      font-size: 16px;
    }

    button {
      padding: 8px 15px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
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

  <div id="results">
    <!-- Results will be displayed here -->
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("search-input").value.toLowerCase();
      var resultsDiv = document.getElementById("results");

      // Clear previous results
      resultsDiv.innerHTML = "";

      // Example data (replace with your actual data source)
      var data = [
        "Apple",
        "Banana",
        "Orange",
        "Grape",
        "Mango",
        "Pineapple",
        "Strawberry",
        "Watermelon"
      ];

      // Filter data based on search term
      for (var i = 0; i < data.length; i++) {
        if (data[i].toLowerCase().includes(searchTerm)) {
          var resultItem = document.createElement("p");
          resultItem.textContent = data[i];
          resultsDiv.appendChild(resultItem);
        }
      }

      // If no results are found
      if (resultsDiv.innerHTML === "") {
        var noResults = document.createElement("p");
        noResults.textContent = "No results found.";
        resultsDiv.appendChild(noResults);
      }
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established and named $conn

// Get the search term from the GET request
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize and escape the search term to prevent SQL injection
$searchTerm = htmlspecialchars($searchTerm);

// Check if the search term is empty
if (empty($searchTerm)) {
  // Display a message or do nothing if no search term is entered
  echo "<p>Please enter a search term.</p>";
} else {
  // Construct the SQL query
  $tableName = 'your_table_name'; // Replace with your table name
  $columnName = 'your_column_name'; // Replace with the column you want to search in
  $sql = "SELECT * FROM $tableName WHERE $columnName LIKE '%$searchTerm%'";


  // Execute the query
  $result = mysqli_query($conn, $sql);  // or whatever method you're using to query

  // Check if the query was successful
  if ($result) {
    // Display the search results
    echo "<h2>Search Results for: '$searchTerm'</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Column 1</th><th>Column 2</th></tr>"; // Adjust based on your table columns

    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr>";
      foreach ($row as $value) {
        echo "<td>" . htmlspecialchars($value) . "</td>"; // Escape for output as well
      }
      echo "</tr>";
    }

    echo "</table>";

  } else {
    // Handle the error
    echo "<p>Error executing the query: " . mysqli_error($conn) . "</p>";
  }
}

?>
