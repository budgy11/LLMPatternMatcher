

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin: 20px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 300px;
    }

    input[type="text"] {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchBox" placeholder="Search..." onkeyup="searchFunction()">
  </div>

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function searchFunction() {
      var searchTerm = document.getElementById("searchBox").value.toLowerCase();
      var resultsDiv = document.getElementById("results");
      resultsDiv.innerHTML = ""; // Clear previous results

      // Dummy data for demonstration - replace with your data source
      var data = [
        "Apple",
        "Banana",
        "Orange",
        "Grape",
        "Kiwi",
        "Mango",
        "Pineapple",
        "Strawberry"
      ];

      for (var i = 0; i < data.length; i++) {
        if (data[i].toLowerCase().indexOf(searchTerm) > -1) {
          var resultItem = document.createElement("p");
          resultItem.textContent = data[i];
          resultItem.style.borderBottom = "1px solid #eee";
          resultItem.style.paddingBottom = "10px";
          resultItem.style.paddingTop = "10px";
          resultItem.style.fontSize = "16px";
          resultItem.onclick = function() {
            // Handle click event here - e.g., navigate to a detail page
            alert("Clicked: " + this.textContent);
          };
          resultsDiv.appendChild(resultItem);
        }
      }
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Function to handle the search query
function performSearch($searchTerm, $table_name, $searchable_columns) {
  global $conn; // Access the database connection

  $searchTerm = "%" . $searchTerm . "%"; // Add wildcards for partial matches

  $sql = "SELECT * FROM $table_name WHERE ";

  $whereClauses = [];
  foreach ($searchable_columns as $column) {
    $whereClauses[] = "$column LIKE '$searchTerm'";
  }

  $sql .= implode(" OR ", $whereClauses);

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    return $result;
  } else {
    return false; // No results found
  }
}


// Example usage:  This part is how you'd integrate it into a form

// Check if the search form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Define the table name and columns to search
  $table_name = "products"; // Replace with your table name
  $searchable_columns = ["name", "description", "category"]; // Replace with your searchable columns

  // Perform the search
  $searchResults = performSearch($searchTerm, $table_name, $searchable_columns);

  if ($searchResults) {
    // Display the search results
    echo "<h2>Search Results for: " . htmlspecialchars($searchTerm) . "</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Name</th><th>Description</th><th>Category</th></tr>";

    while ($row = $searchResults->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . $row["id"] . "</td>";
      echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
      echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
      echo "<td>" . htmlspecialchars($row["category"]) . "</td>";
      echo "</tr>";
    }
    echo "</table>";

  } else {
    echo "<p>No results found for '" . htmlspecialchars($searchTerm) . "'.</p>";
  }
}
?>
