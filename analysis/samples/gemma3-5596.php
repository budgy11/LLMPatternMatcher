

<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    .search-container {
      margin: 20px;
    }

    input[type="text"] {
      width: 300px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50; /* Green */
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <h1>Search</h1>
    <input type="text" id="searchInput" placeholder="Enter search term">
    <button onclick="search()">Search</button>
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;

      // Perform your search logic here
      // Example:  Simple string comparison (replace with your actual search)
      if (searchTerm.length > 0) {
        alert("Searching for: " + searchTerm); // Replace this with your actual search results

        // Example:  Display search results (replace this placeholder)
        // document.getElementById("searchResults").innerHTML = "Search results for '" + searchTerm + "' would go here.";
      } else {
        alert("Please enter a search term.");
      }
    }
  </script>

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
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>


  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;

      //  Add your search logic here.  This example just logs to the console.
      console.log("Search term: " + searchTerm);

      //  Example:  You might want to:
      //  1.  Make an AJAX request to your server to search a database.
      //  2.  Display results on the page.

      //  For example, to trigger a JavaScript search:
      //  alert("Searching for: " + searchTerm);

      //  Or, if you were searching the current page's content:
      //  var content = document.body.innerHTML;
      //  if (content.indexOf(searchTerm) > -1) {
      //    alert("Found " + searchTerm + " on this page!");
      //  } else {
      //    alert("Not found on this page.");
      //  }
    }
  </script>

</body>
</html>


<?php

// Sample data (replace with your actual data source - database, array, etc.)
$products = [
    'apple' => 'Red delicious apple',
    'banana' => 'Yellow banana',
    'orange' => 'Juicy orange',
    'grape' => 'Purple grape',
    'pineapple' => 'Sweet pineapple',
];

// Function to perform the search
function searchProducts($searchTerm, $products) {
    $results = [];

    // Convert search term to lowercase for case-insensitive searching
    $searchTerm = strtolower($searchTerm);

    foreach ($products as $key => $value) {
        // Check if the search term is a substring of the product name
        if (strpos($value, $searchTerm) !== false) {
            $results[$key] = $value;
        }
    }

    return $results;
}

// Get the search term from the GET request (if available)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Perform the search
$searchResults = searchProducts($searchTerm, $products);

?>
