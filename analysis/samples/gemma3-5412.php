
<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
</head>
<body>

  <h1>Search Products</h1>

  <form method="GET" action="">
    <input type="text" name="search" placeholder="Enter search term..." required>
    <button type="submit">Search</button>
  </form>

  <?php
  // --- Process the search (if a form submission)  ---
  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Get the search term from the form
    $search_term = isset($_GET["search"]) ? $_GET["search"] : '';

    // Get the search results
    $results = search_results($search_term);

    // Display the results
    if (count($results) > 0) {
      echo "<h2>Search Results:</h2>";
      echo "<ul>";
      foreach ($results as $result) {
        echo "<li>" . $result['name'] . " - " . $result['description'] . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found for '" . htmlspecialchars($search_term) . "'.</p>";
    }
  }
?>
