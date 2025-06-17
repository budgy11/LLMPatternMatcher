

<!DOCTYPE html>
<html>
<head>
<title>Search Example</title>
<style>
  /* Basic styling - customize as needed */
  #search-bar {
    width: 300px;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    box-sizing: border-box; /* Ensures padding and border are included in width */
  }

  #search-button {
    padding: 8px 15px;
    background-color: #4CAF50; /* Green */
    color: white;
    border: none;
    cursor: pointer;
  }

  #search-button:hover {
    background-color: #3e8e41;
  }

  #results {
    margin-top: 20px;
  }

</style>
</head>
<body>

<h1>Search Example</h1>

<input type="text" id="search-bar" placeholder="Enter search term">
<button id="search-button">Search</button>

<div id="results">
  <!-- Search results will be displayed here -->
</div>

<script>
  // JavaScript code to handle the search
  document.getElementById('search-button').addEventListener('click', function() {
    var searchTerm = document.getElementById('search-bar').value.toLowerCase(); // Get the search term and convert to lowercase
    var resultsDiv = document.getElementById('results');
    resultsDiv.innerHTML = ''; // Clear previous results

    // *** Replace this with your actual search logic ***
    // This is just an example - you'll need to adapt it to your data source
    var data = [
      { title: "Apple iPhone 14", description: "The latest iPhone with amazing features." },
      { title: "Samsung Galaxy S23", description: "A powerful Android phone." },
      { title: "Google Pixel 7", description: "Google's flagship phone." },
      { title: "Amazon Echo Dot", description: "The popular smart speaker." },
      { title: "Sony WH-1000XM5", description: "Noise cancelling headphones." }
    ];

    if (searchTerm === "") {
      resultsDiv.innerHTML = "<p>Please enter a search term.</p>";
      return;
    }

    // Simple search - search by title or description
    data.forEach(function(item) {
      if (item.title.toLowerCase().includes(searchTerm) || item.description.toLowerCase().includes(searchTerm)) {
        var listItem = document.createElement('div');
        listItem.innerHTML = '<strong>' + item.title + '</strong><br>' + item.description;
        listItem.style.margin = '5px 0'; // Add some spacing
        resultsDiv.appendChild(listItem);
      }
    });

    if (resultsDiv.innerHTML === "") {
      resultsDiv.innerHTML = "<p>No results found for '" + searchTerm + "'.</p>";
    }
  });
</script>

</body>
</html>


<?php

// Include your database connection file (assuming it's named 'db_connect.php')
// This file should contain the database connection details.
require_once 'db_connect.php';

// Get the search term from the form (if submitted)
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$search_term = htmlspecialchars(trim($search_term));

// Escape the search term for the database query (prevents SQL injection)
$search_term = mysqli_real_escape_string($conn, $search_term);

//  Option 1: Search in a table named 'products'
//  This example assumes you have a table named 'products' with a column named 'name'

// Build the SQL query
$sql = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'";

// Execute the query
$result = mysqli_query($conn, $sql);

?>
