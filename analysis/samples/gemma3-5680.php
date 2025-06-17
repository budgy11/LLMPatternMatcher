
<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
</head>
<body>

  <h1>Search Products</h1>

  <form method="GET" action="">
    <input type="text" name="search" placeholder="Enter search term...">
    <button type="submit">Search</button>
  </form>

  <?php
    if (isset($_GET['search'])) {
      $search_term = $_GET['search'];
      $results = search_results($search_term);

      if (!empty($results)) {
        echo "<h2>Search Results:</h2>";
        echo "<ul>";
        foreach ($results as $result) {
          echo "<li>" . $result['name'] . " (ID: " . $result['id'] . ")</li>";
        }
        echo "</ul>";
      } else {
        echo "<p>No products found matching your search.</p>";
      }
    }
  ?>
