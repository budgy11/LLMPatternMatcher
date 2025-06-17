
<!DOCTYPE html>
<html>
<head>
  <title>Product Search</title>
</head>
<body>

  <h1>Product Search</h1>

  <form method="GET" action="">
    <input type="text" name="search" placeholder="Search products...">
    <button type="submit">Search</button>
  </form>

  <?php
  // Display the results
  if (mysqli_num_rows($result) > 0) {
    echo "<table border='1'>";
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr>";
      echo "<td>" . $row['name'] . "</td>";
      // You can add other columns here if you want to display other product information
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "<p>No products found matching your search term.</p>";
  }
  ?>
