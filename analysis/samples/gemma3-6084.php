
</body>
</html>


   function searchDatabase($searchTerm) {
     // Replace this with your actual database query logic
     // This is just a placeholder
     $results = [
       "Product A - This is awesome!",
       "Product B - Another great product.",
       "Product C - Not found",
     ];
     return $results;
   }
   
   * **Database Connection:**  You'll need to establish a connection to your database using your database driver (e.g., `mysqli`, `PDO`).
   * **SQL Query:** Construct a SQL query to search your database.  Use `LIKE` for partial matches (e.g., `WHERE name LIKE '%$searchTerm%'`).  **Important:**  *Always* use prepared statements (with `mysqli_stmt` or PDO's prepared statements) to prevent SQL injection attacks.  Don't directly concatenate user input into your SQL query string.
   * **Return Results:**  Return the results of your database query as an array of strings, or an array of objects if you're using objects.

**Example with Prepared Statements (MySQLi):**



<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      width: 300px; /* Adjust as needed */
      margin: 20px auto;
      text-align: center;
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Ensures padding and border are included in width */
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }

    .results {
      margin-top: 20px;
      list-style: none;
      padding: 0;
    }

    .result-item {
      margin-bottom: 10px;
      padding: 10px;
      border: 1px solid #eee;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <div class="results" id="searchResults">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value.toLowerCase();
      var resultsContainer = document.getElementById("searchResults");

      // Example data (replace with your actual data source)
      var data = [
        { title: "Apple iPhone 15", description: "The latest iPhone..." },
        { title: "Samsung Galaxy S23", description: "A great Android phone..." },
        { title: "Google Pixel 7", description: "Powerful camera and AI..." },
        { title: "OnePlus 11", description: "Fast charging and smooth performance..." },
        { title: "Amazon Echo Dot", description: "Voice control and smart home integration..." }
      ];

      // Clear previous results
      resultsContainer.innerHTML = "";

      // Filter data based on the search term
      var filteredData = data.filter(function(item) {
        return item.title.toLowerCase().includes(searchTerm) || item.description.toLowerCase().includes(searchTerm);
      });

      // Display the filtered results
      if (filteredData.length > 0) {
        filteredData.forEach(function(item) {
          var resultItem = document.createElement("div");
          resultItem.classList.add("result-item");
          resultItem.innerHTML = `<strong>${item.title}</strong><br>${item.description}`;
          resultsContainer.appendChild(resultItem);
        });
      } else {
        var noResultsMessage = document.createElement("p");
        noResultsMessage.textContent = "No results found.";
        resultsContainer.appendChild(noResultsMessage);
      }
    }
  </script>

</body>
</html>


<?php

// Basic Search Bar with Simple Text Matching

// Get the search term from the query string
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent XSS attacks
$search_term = htmlspecialchars($search_term);

// Example data to search through (replace with your actual data)
$products = [
    'Apple iPhone 13' => 'A great smartphone',
    'Samsung Galaxy S22' => 'Another excellent smartphone',
    'Google Pixel 6' => 'A powerful camera phone',
    'Laptop Dell XPS 13' => 'A lightweight and powerful laptop',
    'Gaming PC RTX 3070' => 'Ideal for gaming',
];


// Perform the search
$results = [];
foreach ($products as $key => $value) {
    // Case-insensitive search
    if (stripos($key, $search_term) !== false || stripos($value, $search_term) !== false) {
        $results[$key] = $value;
    }
}

?>
