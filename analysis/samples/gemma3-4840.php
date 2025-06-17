

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    /* Basic styling for the search bar */
    #search-container {
      width: 300px;
      margin: 20px auto;
      text-align: center;
    }

    #search-input {
      width: 80%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Important for consistent sizing */
      font-size: 16px;
    }

    #search-button {
      padding: 10px 20px;
      background-color: #4CAF50; /* Green */
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 5px;
      font-size: 16px;
    }

    #search-button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div id="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <button id="search-button">Search</button>
  </div>

  <script>
    // JavaScript to handle search functionality
    document.getElementById('search-button').addEventListener('click', function() {
      var searchTerm = document.getElementById('search-input').value;

      // Perform your search logic here.  This is a placeholder example.
      if (searchTerm) {
        alert("Searching for: " + searchTerm); // Replace with your actual search code
        // Example: Search through an array or database
        // var results = searchArray(searchTerm);
        // document.getElementById('search-container').innerHTML = "Results: " + results.join('<br>');
      } else {
        alert("Please enter a search term.");
      }
    });
  </script>

</body>
</html>


<?php

// Sample data - Replace with your actual data source (e.g., database query)
$data = [
    "apple", "banana", "cherry", "date", "elderberry",
    "fig", "grape", "honeydew", "kiwi", "lemon"
];

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars($searchTerm);

// Perform the search
$searchResults = [];
foreach ($data as $item) {
    $item = htmlspecialchars($item); //Sanitize each item too
    if (stripos($item, $searchTerm) !== false) {
        $searchResults[] = $item;
    }
}

?>
