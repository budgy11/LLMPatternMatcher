
</body>
</html>


<?php

// Assuming you have a database connection established
// (e.g., using mysqli or PDO)
// Replace these with your actual database connection details

// Example using mysqli:
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Get the search term from the search input field
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars($searchTerm);


// SQL query to search in a table (replace 'your_table_name' and 'your_column_name' with your actual values)
$sql = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $searchTerm . "%'";
$result = $conn->query($sql);


?>
