
<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
</head>
<body>

  <h1>Search Your Data</h1>

  <form method="GET" action="">
    <input type="text" name="search" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php

  // Check if a search term was submitted
  if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];

    // Call the searchData function
    $searchResults = searchData($db, $searchTerm);

    // Display the search results
    if (count($searchResults) > 0) {
      echo "<h2>Search Results:</h2>";
      echo "<ul>";
      foreach ($searchResults as $row) {
        echo "<li>" . $row['column_name'] . "</li>"; // Replace 'column_name' with the actual column you want to display
      }
      echo "</ul>";
    } else {
      echo "<p>No results found for '" . $searchTerm . "'.</p>";
    }
  }
  ?>
