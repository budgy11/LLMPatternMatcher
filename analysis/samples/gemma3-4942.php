
<!DOCTYPE html>
<html>
<head>
  <title>Search Example</title>
</head>
<body>

  <h1>Search</h1>

  <form action="" method="post">
    <input type="text" name="search_term" placeholder="Enter search term...">
    <button type="submit">Search</button>
  </form>

</body>
</html>


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
    box-sizing: border-box; /* Ensures padding and border are included in the width */
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

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;
      if (searchTerm.trim() === "") {
        // Do nothing if the search term is empty
        return;
      }

      //  Replace this with your actual search logic
      //  This example just logs the search term to the console.
      console.log("Searching for: " + searchTerm);

      // Simulate getting results (replace with your actual database/array search)
      var results = getSearchResults(searchTerm);

      displaySearchResults(results);
    }

    function getSearchResults(searchTerm) {
      // This is a placeholder function.  Replace this with your search logic.
      // This example returns dummy results based on the search term.

      var results = [];
      if (searchTerm.toLowerCase().includes("apple")) {
        results.push("Apple iPhone 14 Pro");
        results.push("Apple MacBook Pro");
      } else if (searchTerm.toLowerCase().includes("laptop")) {
        results.push("Dell XPS 15");
        results.push("HP Spectre x360");
      } else {
        results.push("Generic Laptop");
        results.push("Generic Desktop");
      }
      return results;
    }

    function displaySearchResults(results) {
      var resultsDiv = document.getElementById("results");
      resultsDiv.innerHTML = "<h3>Search Results:</h3><ul>";
      for (var i = 0; i < results.length; i++) {
        resultsDiv.innerHTML += "<li>" + results[i] + "</li>";
      }
      resultsDiv.innerHTML += "</ul>";
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)

function searchProducts($searchTerm) {
    global $db; // Access the database connection

    $query = "SELECT product_name, description FROM products WHERE product_name LIKE ? OR description LIKE ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", "%" . $searchTerm . "%", "%" . $searchTerm . "%"); // Use prepared statements!
    $stmt->execute();
    $result = $stmt->get_result();

    $searchResults = [];
    while ($row = $result->fetch_assoc()) {
        $searchResults[] = $row['product_name'] . " - " . $row['description'];
    }
    $stmt->close();
    return $searchResults;
}

// Usage inside the JavaScript (after the button click):
var results = searchProducts(document.getElementById("searchInput").value);
displaySearchResults(results);

?>
