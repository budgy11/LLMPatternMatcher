
</body>
</html>


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
      font-size: 16px;
    }

    button {
      padding: 8px 12px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
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
    <h2>Search:</h2>
    <input type="text" id="searchBox" placeholder="Enter search term">
    <button onclick="search()">Search</button>
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchBox").value;
      if (searchTerm === "") {
        alert("Please enter a search term.");
        return;
      }

      //  Replace this with your actual search logic
      //  For example, search through an array, database, or other data source
      var results = performSearch(searchTerm);

      // Display results (example:  display in a div)
      displaySearchResults(results);
    }

    function performSearch(searchTerm) {
      // **Replace this function with your actual search logic**
      // This is just a placeholder.  It returns a dummy array for demonstration.

      var dummyResults = [
        "Result 1: This is a result for " + searchTerm,
        "Result 2: Another result for " + searchTerm,
        "Result 3: More results containing " + searchTerm
      ];
      return dummyResults;
    }

    function displaySearchResults(results) {
      // **Replace this function with your actual logic to display the results**
      // This is just a placeholder.  It displays the results in a div.

      var resultsDiv = document.createElement('div');
      resultsDiv.innerHTML = "<h3>Search Results:</h3>";

      for (var i = 0; i < results.length; i++) {
        var resultItem = document.createElement('p');
        resultItem.innerHTML = results[i];
        resultsDiv.appendChild(resultItem);
      }

      //  Append the results div to the body
      document.body.appendChild(resultsDiv);
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)
// and you want to search a table named 'products' with a column named 'name'

// 1. Get the search term from the input field
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// 2. Sanitize the search term (very important for security)
//  - Remove leading/trailing whitespace
//  - Escape characters to prevent SQL injection
$searchTerm = trim($searchTerm);
$searchTerm = htmlspecialchars($searchTerm); // This is crucial!

// 3. Build the SQL query (using prepared statements is *strongly* recommended)
// Prepared statements prevent SQL injection vulnerabilities.
// This example uses a simple string concatenation, but you should *always* use prepared statements.

$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// 4. Execute the query
$result = mysqli_query($conn, $sql); // Replace $conn with your database connection

// 5. Process the results
?>
