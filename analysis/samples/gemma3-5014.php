
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      margin: 20px;
      text-align: center;
    }

    input[type="text"] {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 300px;
      font-size: 16px;
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50;
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
    <button onclick="performSearch()">Search</button>
  </div>

  <script>
    function performSearch() {
      // Get the value from the input field
      var searchTerm = document.getElementById("searchInput").value;

      // Perform your search logic here.
      // This is just a placeholder example.

      if (searchTerm.trim() === "") {
        alert("Please enter a search term.");
        return;
      }

      // For demonstration, let's just display the search term in an alert
      alert("Searching for: " + searchTerm);

      //  Replace this with your actual search implementation.
      //  You'll likely want to:
      //   1. Send the searchTerm to a server-side script (e.g., a PHP file)
      //   2.  The PHP script would query a database or other data source
      //   3.  The PHP script would return the results to the client-side
      //      (e.g., using JSON or HTML)
      //   4.  Display the results in the HTML.


      // Example of how to display results (replace with your actual data)
      //  const results = [
      //    { title: "Product A", description: "Description of Product A" },
      //    { title: "Product B", description: "Description of Product B" }
      //  ];
      //
      //  let resultHtml = "";
      //  for (let i = 0; i < results.length; i++) {
      //    resultHtml += "<li>" + results[i].title + " - " + results[i].description + "</li>";
      //  }
      //
      //  document.getElementById("searchResults").innerHTML = resultHtml;
    }
  </script>

  <!-- Optional:  A placeholder for the search results (you'd need to populate this with data) -->
  <!-- <div id="searchResults"></div> -->

</body>
</html>


<?php

// Define variables for the search bar and the data to search through
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$data = [
    ['id' => 1, 'name' => 'Apple', 'description' => 'A red fruit'],
    ['id' => 2, 'name' => 'Banana', 'description' => 'A yellow fruit'],
    ['id' => 3, 'name' => 'Orange', 'description' => 'A citrus fruit'],
    ['id' => 4, 'name' => 'Grape', 'description' => 'A small, juicy fruit'],
];


// Sanitize the search term to prevent XSS attacks
$search_term = htmlspecialchars(trim($search_term));


// Perform the search
$results = [];
foreach ($data as $item) {
    $found = false;
    $search_term_lower = strtolower($search_term); // Convert search term to lowercase for case-insensitive search
    $name_lower = strtolower($item['name']);
    $description_lower = strtolower($item['description']);

    if (strpos($name_lower, $search_term_lower) !== false ||
        strpos($description_lower, $search_term_lower) !== false) {
        $found = true;
    }

    if ($found) {
        $results[] = $item;
    }
}


?>
