
<!DOCTYPE html>
<html>
<head>
  <title>Search Results</title>
</head>
<body>

  <h1>Search Results</h1>

  <form action="" method="get">
    <input type="text" name="search" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php if (count($results) > 0): ?>
