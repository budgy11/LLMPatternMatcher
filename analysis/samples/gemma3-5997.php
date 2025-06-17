

<!DOCTYPE html>
<html>
<head>
<title>Search Bar Example</title>
<style>
  .search-container {
    margin-bottom: 10px;
  }
  input[type="text"] {
    width: 300px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
  }
</style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search..." onkeyup="searchFunction()">
  </div>

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function searchFunction() {
      var searchTerm = document.getElementById("searchInput").value.toLowerCase();
      var resultsDiv = document.getElementById("results");
      resultsDiv.innerHTML = ""; // Clear previous results

      // Example data (replace with your actual data source)
      var data = [
        { name: "Apple", description: "A delicious fruit." },
        { name: "Banana", description: "A yellow fruit." },
        { name: "Orange", description: "A citrus fruit." },
        { name: "Grape", description: "Small and juicy." },
        { name: "Mango", description: "Tropical fruit." }
      ];


      // Loop through the data and display matching results
      for (var i = 0; i < data.length; i++) {
        if (data[i].name.toLowerCase().includes(searchTerm) || data[i].description.toLowerCase().includes(searchTerm)) {
          var listItem = document.createElement("p");
          listItem.textContent = data[i].name + " - " + data[i].description;
          resultsDiv.appendChild(listItem);
        }
      }

      // If no results are found, display a message
      if (resultsDiv.innerHTML === "") {
        resultsDiv.innerHTML = "<p>No results found.</p>";
      }
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Replace this with your actual database connection setup

// Example database connection (replace with your details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the search form has been submitted
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the search term from the form
    $searchTerm = $_POST["search_term"];

    // Sanitize the search term (very important to prevent SQL injection)
    $searchTerm = $conn->real_escape_string($searchTerm);

    //  You can customize the query based on your needs
    //  Example: Searching in a table named "products" with a column named "name"
    $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'"; 
    
    // OR, if you want a more exact match (case-insensitive)
    // $sql = "SELECT * FROM products WHERE name LIKE '%" . $conn->real_escape_string($searchTerm) . "%'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output the results
        echo "<h2>Search Results for: " . $searchTerm . "</h2>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Name</th><th>Description</th></tr>"; // Adjust column names as needed

        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["description"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No results found for: " . $searchTerm . "</p>";
    }
}

?>
