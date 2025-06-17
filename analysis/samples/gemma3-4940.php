

<?php
// ... (HTML form as above) ...

// Assuming you have a database connection established
// $conn = mysqli_connect("localhost", "username", "password", "database");

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$searchTerm = htmlspecialchars(trim($searchTerm));

// Prepare the statement
$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE name LIKE ?");

// Bind the parameter
mysqli_stmt_bind_param($stmt, "s", $searchTerm);  // "s" indicates a string parameter

// Execute the statement
mysqli_stmt_execute($stmt);

// Get the result
$result = mysqli_stmt_get_result($stmt);

$searchResults = [];
while ($row = mysqli_fetch_assoc($result)) {
    $searchResults[] = htmlspecialchars($row['name']);
}

// Close the statement
mysqli_stmt_close($stmt);
?>
