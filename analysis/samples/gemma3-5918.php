
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
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 300px;
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

  <div id="results" class="results" style="display: none;">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;
      var resultsDiv = document.getElementById("results");

      if (searchTerm.trim() === "") {
        resultsDiv.style.display = "none";  // Hide results if search term is empty
        return;
      }

      // Replace this with your actual database query and logic
      // This is just a placeholder example
      var results = [];
      if (searchTerm === "example") {
        results = ["Result 1 for example", "Result 2 for example"];
      } else if (searchTerm === "another") {
        results = ["Result 1 for another", "Result 2 for another"];
      } else {
          results = ["No results found for '" + searchTerm + "'"];
      }

      // Clear previous results
      resultsDiv.innerHTML = "";

      // Display the results
      if (results.length > 0) {
        resultsDiv.style.display = "block";
        for (var i = 0; i < results.length; i++) {
          var p = document.createElement("p");
          p.textContent = results[i];
          resultsDiv.appendChild(p);
        }
      } else {
        resultsDiv.style.display = "none";
      }
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// For demonstration purposes, let's use a simple array instead.
$data = [
    ['id' => 1, 'name' => 'Apple'],
    ['id' => 2, 'name' => 'Banana'],
    ['id' => 3, 'name' => 'Orange'],
    ['id' => 4, 'name' => 'Grape'],
    ['id' => 5, 'name' => 'Strawberry']
];


// Function to search data
function searchData($searchTerm, $data) {
    $results = [];

    foreach ($data as $item) {
        // Case-insensitive search
        $name = strtolower($item['name']);  
        $searchTerm = strtolower($searchTerm);

        if (strpos($name, $searchTerm) !== false) {
            $results[] = $item;
        }
    }

    return $results;
}


// Get the search term from the form (e.g., using a GET request)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Perform the search
$searchResults = searchData($searchTerm, $data);

?>
