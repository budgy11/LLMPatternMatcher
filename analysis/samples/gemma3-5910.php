

// Using prepared statements
$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
mysqli_stmt_bind_param($stmt, "s", $searchTerm, "s");  // "s" indicates a string parameter
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// ... Process the result as before


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      width: 300px;
      margin: 20px auto;
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }

    button {
      padding: 10px 15px;
      background-color: #4CAF50; /* Green */
      color: white;
      border: none;
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

  <div id="searchResults">
    <!-- Results will be displayed here -->
  </div>


  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;
      var searchTermEncoded = encodeURIComponent(searchTerm); //Crucial for URL safety

      // You would typically make an AJAX request here to your server
      // to send the searchTerm to your server for searching.

      // For demonstration purposes, we'll just display a basic message.
      // In a real application, you would replace this with your server-side logic.

      var resultsDiv = document.getElementById("searchResults");
      resultsDiv.innerHTML = "<p>Searching for: " + searchTermEncoded + "</p>";
      //  For a real app, you would replace the above with a JSON response
      //  and do more than just display a message.  Also, you would handle
      //  the response gracefully (e.g., show empty results if no results are found).

      // Example JSON response (replace with your actual data):
      /*
      var response = {
          "results": [
              {"title": "Example 1", "description": "This is an example."},
              {"title": "Example 2", "description": "Another example."},
              {"title": "Example 3", "description": "Yet another example."}
          ]
      };

      //  Parse the JSON and display the results
      //  (You'll need to use JSON.parse() here).

      */
    }
  </script>

</body>
</html>


<?php
// Assuming you have a database connection established in a separate file or session
// For demonstration purposes, using a simplified simulation:

$searchTerm = $_GET['searchTerm']; // Get the search term from the URL (GET method)

// Simulate a database search (replace with your actual database query)
$results = [
    ['title' => 'Example 1', 'description' => 'This is an example.'],
    ['title' => 'Example 2', 'description' => 'Another example.'],
    ['title' => 'Example 3', 'description' => 'Yet another example.'],
];

// Filter results based on the search term
$filteredResults = [];
foreach ($results as $result) {
    $title = strtolower($result['title']); // Convert to lowercase for case-insensitive search
    $description = strtolower($result['description']);
    if (strpos(strtolower($title), strtolower($searchTerm)) !== false || strpos(strtolower($description), strtolower($searchTerm)) !== false) {
        $filteredResults[] = $result;
    }
}


// Convert the results to JSON
$json_results = json_encode($filteredResults);

// Set the content type to JSON
header('Content-Type: application/json');

// Send the JSON response
echo $json_results;
?>
