
<!DOCTYPE html>
<html>
<head>
  <title>Search Example</title>
</head>
<body>

  <h1>Search Results</h1>

  <form method="get">
    <input type="text" name="search" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php if (empty($searchResults)): ?>
