

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
    width: 300px;
    box-sizing: border-box;
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
    <input type="text" id="search-input" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("search-input").value;
      var resultsDiv = document.getElementById("results");

      // Example:  Let's say we're searching through a simple array of data
      var data = [
        "Apple",
        "Banana",
        "Orange",
        "Grape",
        "Strawberry",
        "Pineapple",
        "Mango"
      ];

      // Clean the search term (remove whitespace)
      searchTerm = searchTerm.trim();

      // Filter the data based on the search term
      var filteredData = data.filter(function(item) {
        return item.toLowerCase().indexOf(searchTerm.toLowerCase()) !== -1;
      });

      // Display the results
      if (filteredData.length > 0) {
        resultsDiv.innerHTML = "<b>Search Results:</b><br>";
        filteredData.forEach(function(item) {
          resultsDiv.innerHTML += item + "<br>";
        });
      } else {
        resultsDiv.innerHTML = "No results found for '" + searchTerm + "'.";
      }
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// and you have a table named 'products' with a column 'name'
// You need to replace these placeholders with your actual connection and table details.

// Database connection details (replace with your own)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search term from the form (assuming a simple form)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Escape the search term to prevent SQL injection
$searchTerm = $conn->real_escape_string($searchTerm);


// Prepare the SQL query
$query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = $conn->query($query);

?>
