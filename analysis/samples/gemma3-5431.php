
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling - customize as needed */
    .search-container {
      margin: 20px;
      border: 1px solid #ccc;
      padding: 10px;
      border-radius: 5px;
    }

    input[type="text"] {
      width: 300px;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 3px;
      box-sizing: border-box; /* Important for consistent sizing */
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <div id="results">
    <!-- Search results will appear here -->
  </div>


  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;
      var resultsDiv = document.getElementById("results");

      if (searchTerm.trim() === "") {
        resultsDiv.innerHTML = "Please enter a search term.";
        return;
      }

      // **Replace this with your actual search logic**
      // This is just a placeholder to demonstrate the concept
      var data = [
        "Apple",
        "Banana",
        "Orange",
        "Grape",
        "Strawberry"
      ];

      var resultsHtml = "";
      for (var i = 0; i < data.length; i++) {
        if (data[i].toLowerCase().indexOf(searchTerm.toLowerCase()) > -1) {
          resultsHtml += "<p>" + data[i] + "</p>";
        }
      }

      if (resultsHtml === "") {
        resultsDiv.innerHTML = "No results found.";
      } else {
        resultsDiv.innerHTML = resultsHtml;
      }
    }
  </script>

</body>
</html>


<?php
// Assume this is a PHP script that handles the search
// This is just a basic example - adapt to your database

$searchTerm = $_GET["search"]; // Get search term from the URL

// Database connection (replace with your actual credentials)
$dbHost = "localhost";
$dbUser = "your_username";
$dbPassword = "your_password";
$dbName = "your_database";

$conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Example SQL query (replace with your actual query)
$sql = "SELECT * FROM your_table WHERE name LIKE '%" . $searchTerm . "%'";  //Use LIKE for partial matches

$result = $conn->query($sql);

$resultsHtml = "";
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $resultsHtml .= "<p>" . $row["name"] . "</p>"; //Adjust column names as needed
  }
}

$conn->close();

echo $resultsHtml;
?>
