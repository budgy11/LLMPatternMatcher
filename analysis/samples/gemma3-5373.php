

<?php
// ... (Database connection using PDO) ...

$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$search_term = htmlspecialchars($search_term); // Still important for XSS

$stmt = $conn->prepare("SELECT * FROM " . $table_name . " WHERE `name` LIKE :search");
$stmt->bindValue(':search', '%' . $search_term . '%'); // Bind the value
$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($result) {
  echo "<ul>";
  foreach ($result as $row) {
    echo "<li>" . $row['name'] . " - " . $row['description'] . "</li>";
  }
  echo "</ul>";
} else {
  echo "No results found for '" . $search_term . "'.";
}

$conn->close();
?>
