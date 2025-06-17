
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)

// Get the search term from the GET request
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent XSS vulnerabilities
$searchTerm = htmlspecialchars($searchTerm);

//  Optional: Escape the search term for SQL queries (recommended)
//  This example uses a simple escaping, but a more robust escaping method
//  should be used based on your database system.
// $searchTerm = $db->escapeString($searchTerm);

// Prepare the SQL query - *IMPORTANT* to prevent SQL injection
//  This example demonstrates using prepared statements with PDO.
$sql = "SELECT * FROM your_table_name WHERE your_column_name LIKE :searchTerm";
$stmt = $db->prepare($sql); // Replace $db with your database connection object
$stmt->bindValue(':searchTerm', '%' . $searchTerm . '%'); //  Use LIKE with wildcards
$stmt->execute();

// Fetch the results
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Display the search results
?>
