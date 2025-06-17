

<!DOCTYPE html>
<html>
<head>
<title>Search Bar Example</title>
<style>
  .search-container {
    margin: 20px;
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 5px;
    width: 300px; /* Adjust width as needed */
  }

  input[type="text"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
  }
</style>
</head>
<body>

<div class="search-container">
  <input type="text" id="searchInput" placeholder="Search...">
</div>

<div id="results">
  <!-- Search results will be displayed here -->
</div>

<script>
  document.getElementById("searchInput").addEventListener("keyup", function() {
    var searchText = this.value.toLowerCase(); // Convert to lowercase for case-insensitive search

    // Example data (replace with your actual data source)
    var data = [
      { title: "Apple iPhone 13", description: "A powerful smartphone." },
      { title: "Samsung Galaxy S22", description: "Another great smartphone." },
      { title: "Google Pixel 6", description: "Google's flagship phone." },
      { title: "Best PHP Tutorial", description: "Learn PHP programming." },
      { title: "Learn PHP Online", description: "Find online PHP courses." }
    ];

    var results = [];

    for (var i = 0; i < data.length; i++) {
      if (data[i].title.toLowerCase().includes(searchText) ||
          data[i].description.toLowerCase().includes(searchText)) {
        results.push(data[i]);
      }
    }

    // Clear previous results
    document.getElementById("results").innerHTML = "";

    // Display results
    if (results.length > 0) {
      var resultsHtml = "<ul>";
      for (var j = 0; j < results.length; j++) {
        resultsHtml += "<li>" + results[j].title + " - " + results[j].description + "</li>";
      }
      resultsHtml += "</ul>";
      document.getElementById("results").innerHTML = resultsHtml;
    } else {
      document.getElementById("results").innerHTML = "No results found.";
    }
  });
</script>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      width: 300px;
      margin: 20px auto;
      text-align: center;
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Important for consistent sizing */
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50; /* Green */
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 5px;
    }

    button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchBox" placeholder="Search..." onkeyup="searchFunction()">
    <button onclick="searchFunction()">Search</button>
  </div>

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    // Sample data for demonstration
    const data = [
      "Apple", "Banana", "Orange", "Grape", "Strawberry", "Mango", "Pineapple", "Watermelon"
    ];

    function searchFunction() {
      const searchTerm = document.getElementById("searchBox").value.toLowerCase();
      const resultsDiv = document.getElementById("results");
      resultsDiv.innerHTML = ""; // Clear previous results

      if (searchTerm === "") {
        return; // Don't do anything if the search box is empty
      }

      for (let i = 0; i < data.length; i++) {
        const item = data[i].toLowerCase();
        if (item.includes(data[i])) {
          const resultItem = document.createElement("p");
          resultItem.textContent = data[i];
          resultsDiv.appendChild(resultItem);
        }
      }
    }
  </script>

</body>
</html>


<?php

// Database connection details
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Example search query (replace with your actual query)
$searchTerm = $_GET["search"];  // Get the search term from the URL
$sql = "SELECT * FROM your_table WHERE your_column LIKE '%" . $searchTerm . "%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        // Do something with the row data
        echo "ID: " . $row["id"]. " - Name: " . $row["name"]. "<br>";
    }
} else {
    echo "No results found.";
}

$conn->close();
?>
