
<!DOCTYPE html>
<html>
<head>
  <title>Search Example</title>
</head>
<body>

  <h1>Search Results</h1>

  <form method="post" action="">
    <input type="text" name="search" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established
// (e.g., using mysqli or PDO)

// Let's assume we're using mysqli for this example.
// Replace with your actual database connection details.
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search term from the input field
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$searchTerm = htmlspecialchars($searchTerm); // Sanitize input to prevent XSS

?>
