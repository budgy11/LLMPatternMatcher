
</body>
</html>


<!DOCTYPE html>
<html>
<head>
<title>Simple Search Bar</title>
<style>
  .search-container {
    margin: 20px;
  }

  #search-input {
    width: 300px;
    padding: 10px;
    border: 1px solid #ccc;
    box-sizing: border-box; /* Important to include padding and border in width */
  }

  #results {
    margin-top: 20px;
  }
</style>
</head>
<body>

<div class="search-container">
  <h1>Simple Search Bar</h1>
  <input type="text" id="search-input" placeholder="Enter search term...">
  <button onclick="search()">Search</button>
</div>

<div id="results">
  <?php
    // Example data (replace with your database or data source)
    $items = [
      "apple", "banana", "cherry", "date", "elderberry", "fig", "grape"
    ];

    // Get the search term from the input field
    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

    // Perform the search
    if ($searchTerm) {
      $searchResults = [];
      foreach ($items as $item) {
        if (stripos($item, $searchTerm) !== false) { // Case-insensitive search
          $searchResults[] = $item;
        }
      }

      // Display the results
      if (count($searchResults) > 0) {
        echo "<h2>Search Results:</h2>";
        echo "<ul>";
        foreach ($searchResults as $result) {
          echo "<li>" . $result . "</li>";
        }
        echo "</ul>";
      } else {
        echo "<p>No results found for '" . $searchTerm . "'.</p>";
      }
    }
  ?>
