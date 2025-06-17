
</body>
</html>


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

    button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;

      //  Implement your search logic here.
      //  This is just a placeholder.  Replace this with your actual search code.
      if (searchTerm.trim() === "") {
        alert("Please enter a search term.");
        return;
      }

      // Example:  Search through a simple array of items
      var items = ["apple", "banana", "orange", "grape", "kiwi"];

      var results = [];
      for (var i = 0; i < items.length; i++) {
        if (items[i].toLowerCase().indexOf(searchTerm.toLowerCase()) > -1) {
          results.push(items[i]);
        }
      }

      if (results.length > 0) {
        alert("Found matches: " + results.join(", "));
      } else {
        alert("No matches found.");
      }
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search term from the form (or URL parameters)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars($searchTerm); // Prevents XSS attacks
$searchTerm = trim($searchTerm); // Remove leading/trailing whitespace

// Database query (replace with your actual database credentials and table/column names)
$sql = "SELECT * FROM your_table WHERE your_column LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = mysqli_query($conn, $sql);

// Display the search results
echo "<form method='get' action='your_script.php'>
        <input type='text' name='search' value='" . $searchTerm . "' placeholder='Search...' >
        <button type='submit'>Search</button>
      </form>";

if (mysqli_num_rows($result) > 0) {
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>" . $row['your_column'] . "</li>"; // Display the data from your column
    }
    echo "</ul>";
} else {
    echo "<p>No results found for '" . $searchTerm . "'.</p>";
}

// Close the database connection (important for resource management)
mysqli_close($conn);

?>
