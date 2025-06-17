    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    .search-container {
      margin-bottom: 10px;
    }
    input[type="text"] {
      padding: 5px;
      border: 1px solid #ccc;
      width: 300px;
      box-sizing: border-box;
    }
    button {
      padding: 5px 10px;
      background-color: #4CAF50; /* Green */
      color: white;
      border: none;
      cursor: pointer;
      margin-left: 5px;
    }
    .results {
      margin-top: 20px;
      border: 1px solid #ccc;
      padding: 10px;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <div id="searchResults" class="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;
      var resultsDiv = document.getElementById("searchResults");

      if (searchTerm.trim() === "") {
        resultsDiv.innerHTML = ""; // Clear previous results
        return;
      }

      // *** Replace this with your actual search logic ***
      // This is just a placeholder to demonstrate the search function.
      // Replace this with your database query or other search implementation.
      var results = [
        "Result 1: Example Item 1",
        "Result 2: Example Item 2",
        "Result 3: Example Item 3",
        "Result 4: Another item"
      ];

      //  Display the results
      resultsDiv.innerHTML = "<h3>Search Results:</h3>";
      resultsDiv.innerHTML += results.map(function(result) {
        return "<div>" + result + "</div>";
      }).join("");
    }
  </script>

</body>
</html>


<?php

// Assuming you have a search term variable passed from a form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent SQL injection and cross-site scripting (XSS)
$searchTerm = htmlspecialchars(trim($searchTerm));

// Example: Search through a simple array of items
$items = [
    'Apple',
    'Banana',
    'Orange',
    'Grapefruit',
    'Strawberry',
];

// Search logic
$searchResults = [];
if ($searchTerm) {
    foreach ($items as $item) {
        if (stripos($item, $searchTerm) !== false) { // Use stripos for case-insensitive search
            $searchResults[] = $item;
        }
    }
}

?>
