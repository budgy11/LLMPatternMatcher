
<!DOCTYPE html>
<html>
<head>
  <title>Search Products</title>
</head>
<body>

  <h1>Search Products</h1>

  <form method="GET" action="">
    <input type="text" name="search" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php
  // Display the search results
  if ($result->num_rows > 0) {
    echo "<table border='1'>";
    while ($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . $row['name'] . "</td>"; // Replace 'name' with your actual column name
      // Add other columns here as needed
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "<p>No products found matching your search term.</p>";
  }
  ?>
