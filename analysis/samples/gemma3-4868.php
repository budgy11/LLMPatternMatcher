
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin: 20px;
      display: flex;
      align-items: center;
    }

    input[type="text"] {
      width: 300px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      margin-right: 10px;
    }

    button {
      padding: 8px 15px;
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
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="performSearch()">Search</button>
  </div>

  <div id="searchResults" style="margin-top: 20px; display: none;">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function performSearch() {
      var searchTerm = document.getElementById("searchInput").value;
      var searchResultsDiv = document.getElementById("searchResults");

      // **Replace this with your actual search logic**
      // This is a placeholder example.  You'll need to adapt it to your data source.
      if (searchTerm.trim() === "") {
        searchResultsDiv.style.display = "none"; // Hide results if search is empty
        return;
      }

      // Example: Search through a simple array
      var data = [
        "Apple", "Banana", "Orange", "Grapes", "Mango", "Strawberry", "Pineapple"
      ];

      var results = data.filter(function(item) {
        return item.toLowerCase().includes(searchTerm.toLowerCase());
      });

      // Clear previous results
      searchResultsDiv.innerHTML = "";

      // Display results
      if (results.length > 0) {
        results.forEach(function(item) {
          var resultDiv = document.createElement("p");
          resultDiv.textContent = item;
          searchResultsDiv.appendChild(resultDiv);
        });
      } else {
        searchResultsDiv.style.display = "none";
      }
    }
  </script>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      margin: 20px;
    }
    input[type="text"] {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 300px; /* Adjust width as needed */
      font-size: 16px;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <h2>Search:</h2>
    <input type="text" id="search-input" placeholder="Enter search term">
    <button onclick="search()">Search</button>
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("search-input").value;

      // You can do something with the search term here.
      // For example, you could send it to a server-side script
      // to perform a database query.

      // This is a placeholder to demonstrate how you might process the search term.
      console.log("Searching for: " + searchTerm);

      // Example:  Redirect to another page with the search term in the URL
      // window.location.href = "search_results.php?query=" + searchTerm;
    }
  </script>

</body>
</html>


<?php
// Assuming you have a database connection established (e.g., $conn)

if (isset($_GET['query'])) {
  $searchTerm = $_GET['query'];

  // SQL query -  IMPORTANT:  Use prepared statements for security!
  $sql = "SELECT * FROM products WHERE description LIKE '%" . $searchTerm . "%'";  //Replace with your table/column names

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    echo "<h2>Search Results for: " . $searchTerm . "</h2>";
    while ($row = $result->fetch_assoc()) {
      echo "<p>Product Name: " . $row['product_name'] . "</p>";
      echo "<p>Description: " . $row['description'] . "</p>";
      // Add other product details here
    }
  } else {
    echo "<p>No results found for: " . $searchTerm . "</p>";
  }
} else {
  echo "Error: No search term provided.";
}
?>
