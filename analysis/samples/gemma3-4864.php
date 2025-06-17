
<!DOCTYPE html>
<html>
<head>
  <title>PHP Search Bar</title>
</head>
<body>

  <h1>Search Bar</h1>

  <form method="GET">
    <input type="text" name="search" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php
  // Handle the search form submission
  if (isset($_GET['search'])) {
    handleSearch($_GET['search']);
  }
  ?>
