

<!DOCTYPE html>
<html>
<head>
<title>Simple Search Bar</title>
<style>
  .search-container {
    margin: 20px;
    border: 1px solid #ccc;
    padding: 10px;
  }
  .search-input {
    width: 300px;
    padding: 8px;
    border: 1px solid #ccc;
    box-sizing: border-box; /* Important for consistent sizing */
  }
  .search-button {
    padding: 8px 12px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
  }
  .results {
    margin-top: 20px;
  }
</style>
</head>
<body>

<div class="search-container">
  <input type="text" class="search-input" placeholder="Search..." id="searchInput">
  <button class="search-button" onclick="search()">Search</button>
</div>

<div class="results" id="results">
  <!-- Search results will be displayed here -->
</div>

<script>
  function search() {
    var searchTerm = document.getElementById("searchInput").value;
    var resultsDiv = document.getElementById("results");

    // **Replace this with your actual search logic**
    // This is just a placeholder example.
    if (searchTerm.trim() === "") {
      resultsDiv.innerHTML = ""; // Clear previous results
      return;
    }

    // Example:  Let's assume your data is in an array called 'items'
    var items = [
      "Apple",
      "Banana",
      "Orange",
      "Grape",
      "Strawberry"
    ];

    var results = [];
    for (var i = 0; i < items.length; i++) {
      if (items[i].toLowerCase().indexOf(searchTerm.toLowerCase()) > -1) {
        results.push(items[i]);
      }
    }

    if (results.length > 0) {
      var resultsHTML = "<ul>";
      for (var j = 0; j < results.length; j++) {
        resultsHTML += "<li>" + results[j] + "</li>";
      }
      resultsHTML += "</ul>";
      resultsDiv.innerHTML = resultsHTML;
    } else {
      resultsDiv.innerHTML = "No results found.";
    }
  }
</script>

</body>
</html>


<?php
// Assuming you have a database connection established
// (e.g., $conn = mysqli_connect("localhost", "user", "password", "database");)

function searchDatabase($searchTerm) {
  $query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'"; // Example - Adjust the table/column names.
  $result = mysqli_query($conn, $query);

  $products = [];
  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      $products[] = $row;
    }
    mysqli_free_result($result); // Free the result set
  } else {
    // Handle database error
    error_log("Database error: " . mysqli_error($conn));
  }

  return $products;
}

// Call the function
$searchResults = searchDatabase($searchTerm);

// Display the results (similar to the previous example, but using $searchResults)
// ...
?>
