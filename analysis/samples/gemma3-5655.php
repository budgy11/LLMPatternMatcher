    <input type="text" name="search" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $search_term = htmlspecialchars(trim($_POST["search"]));

  // Basic SQL query (VERY basic - needs improvement for security and robustness)
  $sql = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    echo "<ul>";
    while($row = $result->fetch_assoc()) {
      echo "<li>" . $row["name"] . "</li>";
    }
    echo "</ul>";
  } else {
    echo "<p>No results found.</p>";
  }

  $conn->close();
}
?>
