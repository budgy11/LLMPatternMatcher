
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

  //  Example of handling the search in a separate file (search_results.php)
  //  This is just a demonstration; you'll likely want to integrate this
  //  with a database or other data source.

  //  If you uncomment this, you will need to adjust the search_results.php
  //  file to actually do something with the search term.  This is just
  //  illustrative.

  // if(isset($_GET['search_term'])) {
  //   $search_term = $_GET['search_term'];

  //   echo "<p>You searched for: " . htmlspecialchars($search_term) . "</p>";
  //   // Add your search logic here to filter data based on $search_term
  // }

  ?>
