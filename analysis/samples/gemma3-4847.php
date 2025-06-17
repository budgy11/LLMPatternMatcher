
</body>
</html>


<?php

// Assuming you have a database connection established
// This is a simplified example, replace with your actual database connection code

// Example database connection (replace with your actual connection)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // Set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
  die(); // Stop execution if the connection fails
}


// Search term from the input field (e.g., from a form submission)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Optional: Sanitize the search term to prevent SQL injection
$searchTerm = $conn->quote($searchTerm); // Use the PDO quote() method for security

//  (Optional)  Database table name (replace with your actual table name)
$tableName = "products"; // Example: 'products'
$columnName = "product_name"; // Example: 'product_name'
$searchColumn = $columnName; // Keep this consistent with tableName

// Execute the database query
$stmt = $conn->prepare("SELECT * FROM $tableName WHERE $searchColumn LIKE :search");
$stmt->bindParam(':search', $searchTerm);
$stmt->execute();

// Fetch the results
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
