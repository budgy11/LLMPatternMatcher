

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
    }
    .results {
      margin-top: 20px;
      list-style: none;
      padding: 0;
    }
    .result-item {
      padding: 10px;
      border-bottom: 1px solid #eee;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <div class="results" id="searchResults">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value.toLowerCase();
      var resultsContainer = document.getElementById("searchResults");

      // Replace this with your actual data fetching logic.
      // This is just a dummy example using an array.
      var data = [
        "Apple",
        "Banana",
        "Orange",
        "Grape",
        "Strawberry",
        "Pineapple",
        "Mango",
        "Watermelon"
      ];

      // Clear previous results
      resultsContainer.innerHTML = "";

      // Filter the data based on the search term
      var filteredData = data.filter(function(item) {
        return item.toLowerCase().includes(searchTerm);
      });

      // Display the results
      if (filteredData.length > 0) {
        filteredData.forEach(function(item) {
          var resultItem = document.createElement("div");
          resultItem.classList.add("result-item");
          resultItem.textContent = item;
          resultsContainer.appendChild(resultItem);
        });
      } else {
        var noResults = document.createElement("div");
        noResults.textContent = "No results found.";
        resultsContainer.appendChild(noResults);
      }
    }
  </script>

</body>
</html>


<?php

// Configuration (adjust as needed)
$search_term = ""; // Default empty search term
$results_source = "your_database_table"; // Replace with your database table name
$results_limit = 5; // Limit the number of search results shown

// Check if the search form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $search_term = isset($_POST["search_term"]) ? trim($_POST["search_term"]) : "";
}

// Database connection (replace with your database credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // Set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
  die(); // Stop execution if the connection fails
}

// Prepare the search query
$sql = "SELECT * FROM " . $results_source . " WHERE ";
$where_clause = "";
$params = [];

if ($search_term) {
  $where_clause = "LOWER(name) LIKE :search_term OR LOWER(description) LIKE :search_term"; // Adjust fields (name, description)
  $params = ["search_term" => "%" . $search_term . "%"];  // Use '%' for wildcard matching
}


// Execute the query
if ($where_clause) {
  $stmt = $conn->prepare($where_clause);
  $stmt->execute($params);
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
  $results = []; // No search term, return an empty array
}

?>
