
<!DOCTYPE html>
<html>
<head>
  <title>Search Example</title>
</head>
<body>

  <h1>Search</h1>

  <form action="search_results.php" method="GET">
    <input type="text" name="search_term" placeholder="Enter search term...">
    <button type="submit">Search</button>
  </form>

  <?php
  // Example of handling the search and displaying results (search_results.php)
  if (isset($_GET['search_term'])) {
    $search_term = $_GET['search_term'];

    // **IMPORTANT:** Replace this with your actual data source/database query
    // This is just an example using a hardcoded array.
    $data = [
      'apple', 'banana', 'orange', 'grape', 'kiwi', 'mango'
    ];

    $results = [];
    foreach ($data as $item) {
      if (stripos($item, $search_term) !== false) { // Case-insensitive search
        $results[] = $item;
      }
    }

    if (!empty($results)) {
      echo "<h2>Search Results for: " . $search_term . "</h2>";
      echo "<ul>";
      foreach ($results as $result) {
        echo "<li>" . $result . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found for: " . $search_term . "</p>";
    }
  }
?>
