

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin: 20px;
    }

    input[type="text"] {
      width: 300px;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Ensures padding doesn't add to width */
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
    <button id="searchButton" onclick="search()">Search</button>
  </div>

  <div id="searchResults">
    <!-- Results will be displayed here -->
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;
      var resultsDiv = document.getElementById("searchResults");

      if (searchTerm.trim() === "") {
        resultsDiv.innerHTML = "Please enter a search term.";
        return;
      }

      // Replace this with your actual search logic
      // This example just displays the search term as a placeholder result.

      var resultsHTML = "<h3>Search Results:</h3>";
      resultsHTML += resultsHTML + "<p>" + searchTerm + "</p>";

      resultsDiv.innerHTML = resultsHTML;
    }
  </script>

</body>
</html>


<?php
// ... (HTML code from above) ...

$searchTerm = $_GET['search']; // Get search term from URL
$dbHost = 'your_db_host';
$dbName = 'your_db_name';
$dbUser = 'your_db_user';
$dbPass = 'your_db_password';

// Connect to the database
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Prepared statement (recommended)
$stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ?");  // Use LIKE for partial matches
$stmt->bind_param("s", "%" . $searchTerm . "%"); // "s" indicates a string parameter.  % is a wildcard.

// Execute the query
$stmt->execute();

// Get the results
$result = $stmt->get_result();

// Display the results
$searchResultsDiv = document.getElementById("searchResults");
$searchResultsDiv.innerHTML = "";

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $searchResultsDiv.innerHTML += "<p>" . $row["name"] . " - " . $row["description"] . "</p>";
  }
} else {
  $searchResultsDiv.innerHTML = "<p>No results found.</p>";
}

$stmt->close();
$conn->close();
?>
