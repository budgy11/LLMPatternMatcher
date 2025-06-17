

<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    .search-container {
      margin: 20px;
    }
    input[type="text"] {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 300px;
    }
    button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <h2>Search:</h2>
    <input type="text" id="searchInput" placeholder="Enter search term">
    <button onclick="search()">Search</button>
  </div>

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById('searchInput').value;
      var resultsDiv = document.getElementById('results');

      // Clear previous results
      resultsDiv.innerHTML = "";

      // Perform your search logic here
      // Example:  You would typically query a database or search an array.
      // This is just a placeholder.

      if (searchTerm.trim() === "") {
        resultsDiv.innerHTML = "<p>Please enter a search term.</p>";
        return;
      }

      // Placeholder:  Replace this with your actual search logic
      var items = ["apple", "banana", "orange", "grape", "mango"];

      // Simple search:  Convert to lowercase for case-insensitive matching
      var foundItems = items.filter(function(item) {
        return item.toLowerCase().indexOf(searchTerm.toLowerCase()) !== -1;
      });

      if (foundItems.length > 0) {
        foundItems.forEach(function(item) {
          var listItem = document.createElement("li");
          listItem.textContent = item;
          resultsDiv.appendChild(listItem);
        });
      } else {
        resultsDiv.innerHTML = "<p>No results found.</p>";
      }
    }
  </script>

</body>
</html>


<?php

// Assuming you have a search form with an input field named "search_term"
// and a button to submit the search.

// 1. Handle the Form Submission

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the search term from the form
  $search_term = $_POST["search_term"];

  // Sanitize and validate the search term (VERY IMPORTANT!)
  $search_term = htmlspecialchars(trim($search_term)); // Remove HTML tags and whitespace

  // Perform your search logic here
  $results = performSearch($search_term); // Call a function to actually search.

  //  Display the search results
  echo "<div class='search-results'>";
  if (empty($results)) {
    echo "<p>No results found for: " . htmlspecialchars($search_term) . "</p>";
  } else {
    echo "<ul>";
    foreach ($results as $result) {
      echo "<li>" . htmlspecialchars($result) . "</li>";
    }
    echo "</ul>";
  }
  echo "</div>";
}

// 2.  Example Search Function (replace with your actual database query)
function performSearch($search_term) {
  // This is a placeholder.  Replace this with your actual database query.
  // This example just returns a simple array for demonstration.

  $keywords = array(
    "apple",
    "banana",
    "orange",
    "grape",
    "strawberry"
  );

  $results = [];
  foreach ($keywords as $keyword) {
    if (stripos($keyword, $search_term)) { // Case-insensitive search
      $results[] = $keyword;
    }
  }
  return $results;
}

?>
