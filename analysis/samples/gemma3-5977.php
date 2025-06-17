
</body>
</html>


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
      width: 300px; /* Adjust as needed */
    }

    button {
      padding: 8px 12px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <button onclick="performSearch()">Search</button>
  </div>

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function performSearch() {
      var searchTerm = document.getElementById("search-input").value;

      if (searchTerm.trim() === "") {
        // Handle empty search
        return;
      }

      // Simulate a search (replace with your actual search logic)
      var results = simulateSearch(searchTerm);

      // Display the search results
      displaySearchResults(results);
    }

    function simulateSearch(searchTerm) {
      // Replace this with your actual search logic.
      // This is just a placeholder to demonstrate the functionality.
      var results = [
        "Result 1 for " + searchTerm,
        "Result 2 for " + searchTerm,
        "Result 3 for " + searchTerm,
        "Another result for " + searchTerm
      ];
      return results;
    }

    function displaySearchResults(results) {
      var resultsDiv = document.getElementById("results");
      resultsDiv.innerHTML = "<h3>Search Results:</h3>";
      if (results.length === 0) {
        resultsDiv.innerHTML += "<p>No results found.</p>";
      } else {
        resultsDiv.innerHTML += "<ul>";
        for (var i = 0; i < results.length; i++) {
          resultsDiv.innerHTML += "<li>" + results.charAt(i) + "</li>";
        }
        resultsDiv.innerHTML += "</ul>";
      }
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Replace 'your_db_connection' with your actual connection object.
//  You'll need to properly configure your database connection.

$db = new mysqli('localhost', 'username', 'password', 'database_name');

// Check for connection errors
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Get the search term from the query string (e.g., from a form submission)
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$search_term = htmlspecialchars($search_term); // Sanitize for security

// Validate the search term (optional, but recommended)
if (empty($search_term)) {
    $results = []; // Or return a message like "Please enter a search term."
} else {
    // Perform the database query
    $query = "SELECT * FROM your_table_name WHERE column_to_search LIKE '%" . $search_term . "%'";

    $result = $db->query($query);

    if ($result->num_rows > 0) {
        $results = [];
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
    } else {
        $results = [];  // No results found
    }
}

?>
