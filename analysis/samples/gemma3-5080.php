

<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      margin: 20px;
    }
    input[type="text"] {
      width: 300px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <h1>Search</h1>
    <input type="text" id="searchInput" placeholder="Enter search term">
    <button onclick="search()">Search</button>
  </div>

  <script>
    function search() {
      // Get the search term from the input field
      var searchTerm = document.getElementById("searchInput").value;

      // Basic search logic (replace with your actual search implementation)
      if (searchTerm.trim() === "") {
        alert("Please enter a search term.");
        return;
      }

      // Example:  Convert to lowercase for case-insensitive search
      searchTerm = searchTerm.toLowerCase();

      // Simulate a search (replace with your database/array search)
      var results = simulateSearch(searchTerm);

      // Display the results (replace with your results display logic)
      displaySearchResults(results);
    }


    // Simulate a search function (replace with your actual search logic)
    function simulateSearch(searchTerm) {
      // Example:  Returns some dummy results
      var results = [
        { title: "PHP Tutorial", description: "Learn about PHP programming." },
        { title: "JavaScript Basics", description: "Introduction to JavaScript." },
        { title: "Database Search Example", description: "Searching using a database." },
        { title: "Web Development", description: "Everything you need to know about web development." }
      ];

      // Filter the results based on the search term
      var filteredResults = results.filter(function(result) {
        return result.title.toLowerCase().includes(searchTerm);
      });

      return filteredResults;
    }


    function displaySearchResults(results) {
      // This is where you'd display the results (e.g., in a table, list, etc.)
      // For now, just a simple console log
      console.log("Search Results:");
      results.forEach(function(result) {
        console.log(`- ${result.title} - ${result.description}`);
      });
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established
// ... (your database connection code) ...

// Get the search term
$searchTerm = $_GET["searchInput"]; // Get from the URL (e.g., ?searchInput=keyword)

// Sanitize and escape the search term
$searchTerm = $mysqli->real_escape_string($searchTerm);

// SQL query (example, adjust to your table and column names)
$sql = "SELECT * FROM products WHERE title LIKE '%" . $searchTerm . "%'";

$result = $mysqli->query($sql);

if ($result) {
  // Process the results
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      // Display the results
      echo "<div>" . $row["title"] . " - " . $row["description"] . "</div>";
    }
  } else {
    echo "<p>No results found.</p>";
  }

  $result->free(); // Free the result set
} else {
  echo "<p>Error executing query.</p>";
}

?>
