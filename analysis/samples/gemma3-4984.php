
</body>
</html>


<?php
// (Database connection code here - establish connection)

// ... (rest of the code) ...

$searchTerm = htmlspecialchars($searchTerm);

// Sanitize the search term to prevent SQL injection
$safeSearchTerm = $mysqli->real_escape_string($searchTerm);

// Build the SQL query
$query = "SELECT * FROM products WHERE name LIKE '%" . $safeSearchTerm . "%'";  // Use LIKE for partial matches

$result = $mysqli->query($query);

// ... (rest of the code - process the query result) ...


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      width: 300px; /* Adjust as needed */
      margin: 0 auto; /* Center the container */
      font-family: sans-serif;
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Ensures padding and border are included in the element's total width */
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="search-input" placeholder="Search...">
  </div>

  <script>
    // JavaScript to handle the search functionality
    document.getElementById('search-input').addEventListener('keyup', function() {
      var searchTerm = this.value.toLowerCase();

      //  Example:  Replace this with your actual data source and search logic
      var data = [
        "Apple",
        "Banana",
        "Orange",
        "Grape",
        "Strawberry"
      ];

      var results = [];
      for (var i = 0; i < data.length; i++) {
        if (data[i].toLowerCase().includes(searchTerm)) {
          results.push(data[i]);
        }
      }

      // Display the results (replace with your desired output method)
      document.getElementById('search-results').innerHTML = ""; // Clear previous results
      if (results.length > 0) {
        var resultsHTML = "<ul>";
        for (var i = 0; i < results.length; i++) {
          resultsHTML += "<li>" + results[i] + "</li>";
        }
        resultsHTML += "</ul>";
        document.getElementById('search-results').innerHTML = resultsHTML;
      } else {
        document.getElementById('search-results').innerHTML = "No results found.";
      }
    });
  </script>

  <!--  Area to display the search results -->
  <div id="search-results"></div>

</body>
</html>


<?php

//  Assume you have a database connection established in a separate part of your script
//  $db = mysqli_connect("your_host", "your_user", "your_password", "your_database");

// ... (Code to establish the database connection)

// Example database query (replace with your actual query)
$searchTerm = $_GET['search']; // Get the search term from the URL
$query = "SELECT * FROM your_table WHERE name LIKE '%" . $searchTerm . "%'"; // Simple LIKE query

$result = mysqli_query($db, $query);

if ($result) {
  // Process the result set (similar to the JavaScript part)
  $searchResults = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $searchResults[] = $row['name']; // Or whatever the relevant column is
  }

  // Display the results
  echo "<ul>";
  if (count($searchResults) > 0) {
    foreach ($searchResults as $result) {
      echo "<li>" . $result . "</li>";
    }
  } else {
    echo "<li>No results found.</li>";
  }
  echo "</ul>";

  // Close the database connection (important!)
  mysqli_close($db);

} else {
  echo "Error running query.";
}
?>
