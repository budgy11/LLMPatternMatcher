

<?php
// Assuming you have a database connection established in $conn

$searchTerm = isset($_POST['search_term']) ? $_POST['search_term'] : '';

$searchTerm = htmlspecialchars($searchTerm);

// Prepare the SQL query with a placeholder
$query = "SELECT * FROM your_table WHERE your_column LIKE ?";  // Replace with your actual table and column names

// Create a PDO statement
$stmt = $conn->prepare($query);

// Bind the parameter to the statement
$stmt->bindValue(':search_term', '%' . $searchTerm . '%'); // The '%' characters are for wildcard searches

// Execute the query
$stmt->execute();

// Fetch the results
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Output (same as before)
// ...
?>
