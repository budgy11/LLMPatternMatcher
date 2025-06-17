
<!DOCTYPE html>
<html>
<head>
  <title>Search Example</title>
</head>
<body>

  <h1>Search</h1>

  <form action="" method="GET">
    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php

  // Check if the search term is submitted
  if (isset($_GET['search_term'])) {
    $search_term = $_GET['search_term'];
    // Perform the search
    performSearch($search_term, 'products'); // Assuming your table is named 'products'
  }

  ?>
