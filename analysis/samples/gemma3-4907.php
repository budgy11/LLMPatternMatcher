

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin-bottom: 10px;
    }
    input[type="text"] {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 300px; /* Adjust width as needed */
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
      var searchTerm = document.getElementById("search-input").value.toLowerCase();
      var resultsDiv = document.getElementById("results");

      // Replace this with your actual search logic.
      // This example just demonstrates how to display some placeholder results.

      if (searchTerm === "") {
        resultsDiv.innerHTML = "";
        return;
      }

      // Example:  Let's pretend we're searching a list of fruits.
      var fruits = ["apple", "banana", "orange", "grape", "kiwi"];

      var results = fruits.filter(function(fruit) {
        return fruit.toLowerCase().includes(searchTerm);
      });

      if (results.length > 0) {
        resultsDiv.innerHTML = "Search results:<br>";
        results.forEach(function(fruit) {
          resultsDiv.innerHTML += fruit + "<br>";
        });
      } else {
        resultsDiv.innerHTML = "No results found.";
      }
    }
  </script>

</body>
</html>


<?php

// Assuming you're serving this from a web server (like Apache)
// and you have a database connection established (e.g., using mysqli)

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get the search term from the form (assuming a simple HTML form)
if (isset($_GET['search'])) {
  $searchTerm = $conn->real_escape_string($_GET['search']); // Escape to prevent SQL injection
} else {
  $searchTerm = ""; // Default to empty if no search term is provided
}


// Sanitize the search term (optional, but recommended for better user experience)
$searchTerm = trim($searchTerm); // Remove leading/trailing whitespace
$searchTerm = htmlspecialchars($searchTerm);  // Convert to HTML entities to prevent XSS


// Perform the search (example: searching a 'products' table)
$query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'"; // Adjust 'products' and 'name' to your table and column names
$result = $conn->query($query);

?>
