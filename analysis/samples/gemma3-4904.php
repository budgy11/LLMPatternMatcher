

<?php

// ... (Database Connection - Same as before)

// Get the search term
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (still important, but less critical with prepared statements)
$searchTerm = htmlspecialchars($searchTerm);


// Prepared statement (adapt to your database library)
$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE name LIKE ?"); // Adapt for mysqli

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $searchTerm); // 's' indicates a string
    mysqli_stmt_execute($stmt);

    // Fetch the results
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<p>" . htmlspecialchars($row['name']) . "</p>";
    }

    mysqli_stmt_close($stmt); // Close the statement
} else {
    echo "<p>Error preparing statement.</p>";
}

// ... (Close database connection)


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
</head>
<body>

  <h1>Search Our Website</h1>

  <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
