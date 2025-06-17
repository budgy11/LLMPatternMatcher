

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
      box-sizing: border-box; /* Include padding and border in the element's total width and height */
    }

    button {
      padding: 8px 15px;
      background-color: #4CAF50; /* Green */
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
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <div id="searchResults">
    <!-- Results will be displayed here -->
  </div>


  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value.toLowerCase();
      var searchResultsDiv = document.getElementById("searchResults");
      searchResultsDiv.innerHTML = ""; // Clear previous results

      // Replace this with your actual data source (e.g., database query)
      var data = [
        "Apple",
        "Banana",
        "Orange",
        "Grape",
        "Pineapple",
        "Strawberry",
        "Cherry"
      ];

      for (var i = 0; i < data.length; i++) {
        var term = data[i].toLowerCase();
        if (term.indexOf(searchTerm) !== -1) {
          var resultItem = document.createElement("p");
          resultItem.textContent = data[i];
          searchResultsDiv.appendChild(resultItem);
        }
      }
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established and a database table named 'products'
// with a column named 'name' for searching.  Replace these with your actual details.

// Example database connection (replace with your actual credentials)
// $db = new mysqli("localhost", "username", "password", "database_name");
// if ($db->connect_error) {
//     die("Connection failed: " . $db->connect_error);
// }

// Check if the search form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Sanitize the search term (important to prevent SQL injection)
  $searchTerm = $db->real_escape_string($searchTerm);

  // Perform the search in the database
  $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
  $result = $db->query($sql);

  // Display the search results
  if ($result->num_rows > 0) {
    echo "<h2>Search Results for: " . $searchTerm . "</h2>";
    echo "<table border='1'>";
    while ($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . $row["name"] . "</td>";
      // Add other columns from the 'products' table here
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "<p>No results found for: " . $searchTerm . "</p>";
  }
}
?>
