
</body>
</html>


<?php

// Assuming you have a database connection established
// (e.g., using mysqli or PDO)
// For demonstration, we'll simulate a database query.

// Simulated database connection (replace with your actual connection)
$conn = new mysqli("localhost", "username", "password", "database_name");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Simulate data (replace with your actual database query)
$data = [
    ["id" => 1, "name" => "Apple", "description" => "A red fruit."],
    ["id" => 2, "name" => "Banana", "description" => "A yellow fruit."],
    ["id" => 3, "name" => "Orange", "description" => "A citrus fruit."],
    ["id" => 4, "name" => "Grape", "description" => "Small, sweet fruit."]
];

//  Search term (from the input field)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Escape the search term to prevent SQL injection
$searchTerm = $conn->real_escape_string($searchTerm);


// Build the SQL query
$sql = "SELECT * FROM your_table_name WHERE name LIKE '%" . $searchTerm . "%' OR description LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = $conn->query($sql);

?>
