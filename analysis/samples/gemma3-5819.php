  <input type="text" name="search_term" placeholder="Search..." value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>">
  <input type="submit" value="Search">
</form>

<?php

// 2. PHP Code to Handle the Search
if (isset($_GET['search_term'])) {
  $search_term = htmlspecialchars($_GET['search_term']);  // Sanitize input
  $search_term = trim($search_term); //Remove whitespace

  // *** Replace this with your actual search logic ***

  // Example 1: Simple string search against a string
  // $results = array("apple", "banana", "orange");
  // $results = array_filter($results, function($item) use ($search_term) {
  //   return stripos($item, $search_term) !== false; // Case-insensitive search
  // });

  // Example 2: Search in a database (using MySQLi - preferred)
  // require_once 'config.php';  // Assuming you have your database credentials in config.php
  // $mysqli = new mysqli($host, $user, $password, $database);
  // if ($mysqli->connect_error) {
  //   die("Connection failed: " . $mysqli->connect_error);
  // }

  // $query = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'";
  // $result = $mysqli->query($query);

  // if ($result->num_rows > 0) {
  //   echo "<h2>Search Results for: " . $search_term . "</h2>";
  //   while($row = $result->fetch_assoc()) {
  //     echo "<p>" . $row['name'] . " - " . $row['description'] . "</p>";
  //   }
  // } else {
  //   echo "<p>No results found for: " . $search_term . "</p>";
  // }
  // $mysqli->close();



  // Example 3: Placeholder for your search implementation
  echo "<p>Searching for: " . $search_term . "</p>";
  echo "<p>Your search logic goes here.</p>";  // Implement your actual search
}

?>
