
</body>
</html>


<?php

// Example database connection (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

try {
  $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}


function performSearch($searchTerm) {
  global $pdo;

  $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE :searchTerm OR description LIKE :searchTerm");
  $searchTerm = "%" . $searchTerm . "%"; // Use % as wildcards for LIKE operator
  $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
  $stmt->execute();

  $results = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch results as an associative array

  return $results;
}

function displaySearchResults($searchResults) {
  echo "<h2>Search Results for: " . htmlspecialchars($searchTerm) . "</h2>";
  if (empty($searchResults)) {
    echo "<p>No results found.</p>";
  } else {
    echo "<ul>";
    foreach ($searchResults as $row) {
      echo "<li>" . htmlspecialchars($row['name']) . " - " . htmlspecialchars($row['description']) . "</li>";
    }
    echo "</ul>";
  }
}
?>
