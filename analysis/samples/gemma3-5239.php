
<!DOCTYPE html>
<html>
<head>
  <title>Product Search</title>
</head>
<body>

  <h1>Product Search</h1>

  <form method="GET" action="">
    <input type="text" name="search" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php
  if ($result) {
    if (mysqli_num_rows($result) > 0) {
      echo "<ul>";
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>" . $row['name'] . " - " . $row['description'] . "</li>"; // Adjust based on your table's columns
      }
      echo "</ul>";
    } else {
      echo "<p>No products found matching your search.</p>";
    }
  } else {
    echo "<p>Error executing query.</p>";
  }

  // Close the database connection (important!)
  mysqli_close($conn);
  ?>
