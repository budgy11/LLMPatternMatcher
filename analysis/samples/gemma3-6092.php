

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin: 20px;
    }

    input[type="text"] {
      padding: 10px;
      border: 1px solid #ccc;
      width: 300px;
      font-size: 16px;
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <h2>Search:</h2>
    <input type="text" id="searchInput" placeholder="Enter search term...">
    <button onclick="search()">Search</button>
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;

      //  Replace this with your actual search logic.
      //  This is a placeholder for demonstration purposes.

      if (searchTerm.trim() === "") {
        alert("Please enter a search term.");
        return;
      }

      // Example:  Display the search term in an alert
      alert("You searched for: " + searchTerm);

      //  More sophisticated search logic would go here, 
      //  e.g., searching a database, a list, etc.
    }
  </script>

</body>
</html>


<?php

// Check if the search form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the search term from the form
  $searchTerm = isset($_POST["search_term"]) ? trim($_POST["search_term"]) : "";

  // Sanitize the search term - IMPORTANT for security
  $searchTerm = htmlspecialchars($searchTerm);

  // Perform your search logic here.  This is just a basic example.
  // You'll likely replace this with your actual database query or search algorithm.
  $searchResults = [];

  if ($searchTerm) {
    // Example:  Assume you have an array of data to search through
    $data = [
      "apple", "banana", "cherry", "date", "elderberry",
      "fig", "grape", "honeydew", "kiwi", "lemon"
    ];

    foreach ($data as $item) {
      if (strpos($item, $searchTerm) !== false) { // Case-sensitive search
        $searchResults[] = $item;
      }
    }
  }

  // Display the results
  if (empty($searchResults)) {
    echo "<p>No results found for: " . $searchTerm . "</p>";
  } else {
    echo "<p>Search Results for: " . $searchTerm . "</p>";
    echo "<ul>";
    foreach ($searchResults as $result) {
      echo "<li>" . $result . "</li>";
    }
    echo "</ul>";
  }
}
?>
