
</body>
</html>


<?php
// Assuming you have a database connection established in $conn

if (isset($_GET['search']) && !empty($_GET['search'])) {
  $searchTerm = htmlspecialchars($_GET['search']);

  $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<p>Product: " . $row['name'] . ", Description: " . $row['description'] . "</p>";
    }
    mysqli_free_result($result); // Free the result set
  } else {
    echo "<p>Error executing query.</p>";
  }
}
?>
