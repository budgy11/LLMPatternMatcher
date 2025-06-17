  <button type="submit">Search</button>
</form>


<?php

// Assuming you have a database connection established using PDO

// 1. Get the search term from the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $searchTerm = isset($_POST["search_term"]) ? trim($_POST["search_term"]) : "";
} else {
  $searchTerm = "";
}

// 2. Sanitize and Escape (though less critical with prepared statements)
$searchTerm = $searchTerm; // No need for mysqli_real_escape_string

// 3. Perform the Search Query using Prepared Statements
$sql = "SELECT * FROM products WHERE name LIKE :searchTerm";
$stmt = $pdo->prepare($sql); // Assuming $pdo is your PDO object
$stmt->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);
$stmt->execute();

// 4. Execute the Query
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 5. Display the Results
if ($result) {
  echo "<h2>Search Results for: " . $searchTerm . "</h2>";
  echo "<table border='1'>";
  echo "<thead><tr><th>ID</th><th>Name</th><th>Price</th></tr></thead>";
  foreach ($result as $row) {
    echo "<tr>";
    echo "<td>" . $row["id"] . "</td>";
    echo "<td>" . $row["name"] . "</td>";
    echo "<td>" . $row["price"] . "</td>";
    echo "</tr>";
  }
  echo "</table>";
} else {
  echo "<p>No products found matching your search term.</p>";
}
?>
