
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      width: 300px;
      margin: 20px auto;
      text-align: center;
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin: 0 5px;
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

  <script>
    function search() {
      // Get the search term from the input field
      var searchTerm = document.getElementById("searchInput").value;

      // Perform your search logic here
      // This is just a placeholder. Replace with your actual search code.

      // Example:  Display the search term in an alert
      //alert("You searched for: " + searchTerm);

      // Example:  (Simulated)  Search through an array (replace with your data source)
      var data = [
        "Apple",
        "Banana",
        "Orange",
        "Grape",
        "Pineapple"
      ];
      var results = [];
      for (var i = 0; i < data.length; i++) {
        if (data[i].toLowerCase().indexOf(searchTerm.toLowerCase()) !== -1) {
          results.push(data[i]);
        }
      }

      // Display the results (replace with your actual display logic)
      if (results.length > 0) {
        alert("Search results:
" + results.join("
"));
      } else {
        alert("No results found for: " + searchTerm);
      }


    }
  </script>

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
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 300px;
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50; /* Green */
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
    <input type="text" id="searchInput" placeholder="Enter search term...">
    <button onclick="search()">Search</button>
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;

      // Perform your search logic here.  For example, searching a database.
      // This is just a placeholder example.

      if (searchTerm.trim() === "") {
        alert("Please enter a search term.");
        return;
      }

      // Simulate a search result (replace with your actual search)
      var results = simulateSearchResults(searchTerm);

      // Display the search results
      displaySearchResults(results);
    }

    function simulateSearchResults(searchTerm) {
      //  Replace this with your real database query or search logic.
      var results = [
        "Result 1 for " + searchTerm,
        "Result 2 for " + searchTerm,
        "Result 3 for " + searchTerm
      ];
      return results;
    }

    function displaySearchResults(results) {
      var resultsContainer = document.getElementById("results");
      if (!resultsContainer) {
        resultsContainer = document.createElement("div");
        resultsContainer.id = "results";
        document.body.appendChild(resultsContainer);
      }

      // Clear previous results (optional)
      while (resultsContainer.firstChild) {
        resultsContainer.removeChild(resultsContainer.firstChild);
      }

      for (var i = 0; i < results.length; i++) {
        var resultItem = document.createElement("p");
        resultItem.textContent = results[i];
        resultsContainer.appendChild(resultItem);
      }
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search term from the user
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent SQL injection
$searchTerm = htmlspecialchars(trim($searchTerm));

// Optional: Add a default value if no search term is provided
if (empty($searchTerm)) {
  $searchTerm = '';
}

// SQL query -  This example assumes a table named 'products' with a 'name' column.
//  ADJUST THIS TO MATCH YOUR DATABASE SCHEMA
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if ($result) {
  // Display the search results
  echo "<form action=\"search.php\" method=\"get\">
        <input type=\"text\" name=\"search\" placeholder=\"Search...\" value=\"" . htmlspecialchars($searchTerm) . "\">
        <button type=\"submit\">Search</button>
      </form>";

  echo "<h2>Search Results:</h2>";

  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<div>";
      echo "<h3>" . $row['name'] . "</h3>";
      // Display other product details here (e.g., price, description)
      echo "<p>Price: $" . $row['price'] . "</p>";
      echo "<p>Description: " . $row['description'] . "</p>";
      echo "</div>";
    }
  } else {
    echo "<p>No results found.</p>";
  }

} else {
  // Handle database error
  echo "<p>Error: " . mysqli_error($conn) . "</p>";
}

// Close the database connection (important!)
mysqli_close($conn);
?>
