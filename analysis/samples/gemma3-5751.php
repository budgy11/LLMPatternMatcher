

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
    padding: 8px 15px;
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
  <input type="text" id="searchInput" placeholder="Search...">
  <button onclick="search()">Search</button>
</div>

<div id="searchResults">
  <!-- Results will be displayed here -->
</div>

<script>
function search() {
  var searchTerm = document.getElementById("searchInput").value;
  var searchResultsDiv = document.getElementById("searchResults");

  // Clear previous results
  searchResultsDiv.innerHTML = "";

  // Perform your search logic here
  // This is a placeholder - replace with your actual search code
  if (searchTerm.trim() === "") {
    return; // Don't display results if search term is empty
  }

  // Example:  Simulated search - replace with your database query
  var data = [
    { title: "Apple iPhone 13", description: "The latest iPhone" },
    { title: "Samsung Galaxy S22", description: "A great Android phone" },
    { title: "Google Pixel 6", description: "Google's flagship phone" },
    { title: "Red Hat Linux", description: "A popular Linux distribution" },
  ];

  // Filter the data based on the search term
  var filteredData = data.filter(function(item) {
    return item.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
           item.description.toLowerCase().includes(searchTerm.toLowerCase());
  });

  // Display the results
  if (filteredData.length > 0) {
    filteredData.forEach(function(item) {
      var resultDiv = document.createElement("div");
      resultDiv.innerHTML = `<strong>${item.title}</strong><br>${item.description}`;
      resultDiv.appendChild(resultDiv);
    });
    searchResultsDiv.appendChild(resultDiv);
  } else {
    var noResultsDiv = document.createElement("div");
    noResultsDiv.innerHTML = "No results found.";
    searchResultsDiv.appendChild(noResultsDiv);
  }
}
</script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., using mysqli or PDO)
// Let's use mysqli for demonstration
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

// Get the search term from the search bar (using $_GET for simplicity)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$searchTerm = htmlspecialchars($searchTerm); // Sanitize to prevent XSS

?>
