

<?php
// Assuming you have a database connection established (e.g., PDO)

if (isset($_GET['search'])) {
  $searchTerm = $_GET['search'];
  $searchTerm = $pdo->prepare("SELECT * FROM products WHERE name LIKE ?");
  $searchTerm->bindValue(1, '%' . $searchTerm . '%');
  $searchTerm->execute();

  // ... Display the results ...
}
?>
