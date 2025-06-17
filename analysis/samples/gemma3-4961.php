
</body>
</html>


<?php
// Assuming you have a database connection established

// Sample database connection (replace with your credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// ... (rest of the code, including the searchItems function) ...

// Example of fetching items from a database:
$items = [];
try {
    $stmt = $conn->prepare("SELECT item_name FROM items_table");
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch(PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// ... (rest of the search logic) ...
?>
