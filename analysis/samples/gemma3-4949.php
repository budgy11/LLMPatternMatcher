

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin: 20px;
      border: 1px solid #ccc;
      padding: 10px;
      border-radius: 5px;
    }

    input[type="text"] {
      width: 300px;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      padding: 8px 15px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
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

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;

      // **Important: Replace this with your actual search logic.**
      // This is just a placeholder.  You'll need to adapt it to
      // your data source and how you want to filter it.

      if (searchTerm.trim() === "") {
        return; // Don't perform search if the input is empty
      }

      // Example:  Searching through a simple array of strings
      var data = ["apple", "banana", "orange", "grape", "watermelon"];

      var results = data.filter(function(item) {
        return item.toLowerCase().includes(searchTerm.toLowerCase());
      });

      // Display the results (replace with your desired output)
      var resultsString = "";
      if (results.length > 0) {
        resultsString = "<ul>";
        for (var i = 0; i < results.length; i++) {
          resultsString += "<li>" + results[i] + "</li>";
        }
        resultsString += "</ul>";
      } else {
        resultsString = "No results found.";
      }

      // Display the results in a designated area (e.g., a div with id "searchResults")
      document.getElementById("searchResults").innerHTML = resultsString;
    }
  </script>

  <div id="searchResults" style="margin-top: 20px;">
  </div>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db connection)

// Function to handle the search
function searchData($search_term, $table_name, $column_name) {
  global $db; // Access the database connection

  $sql = "SELECT * FROM " . $table_name . " WHERE " . $column_name . " LIKE '%" . $db->real_escape_string($search_term) . "%'";

  $result = $db->query($sql);

  if ($result->num_rows > 0) {
    return $result;
  } else {
    return false; // No results found
  }
}

// Get the search term from the form
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Example usage - Replace 'products' and 'name' with your actual table and column
if (isset($_GET['search'])) {
  $results = searchData($search_term, 'products', 'name');

  if ($results) {
    echo "<h2>Search Results for: " . htmlspecialchars($search_term) . "</h2>";
    echo "<table border='1'>";
    while ($row = $results->fetch_assoc()) {
      echo "<tr>";
      foreach ($row as $key => $value) {
        echo "<td>" . htmlspecialchars($value) . "</td>";
      }
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "<p>No results found for '" . htmlspecialchars($search_term) . "'.</p>";
  }
}

?>
