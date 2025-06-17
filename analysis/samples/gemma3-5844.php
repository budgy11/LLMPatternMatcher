
<!DOCTYPE html>
<html>
<head>
    <title>Search Example</title>
</head>
<body>

    <h2>Search</h2>

    <form action="" method="get">
        <input type="text" name="search" placeholder="Enter search term">
        <button type="submit">Search</button>
    </form>

</body>
</html>


<?php
// Assuming you have a database connection established (e.g., $db)

// Get the search query from the form
$search_query = $_GET['search'];

// Sanitize the query (important, but not sufficient on its own)
$safe_query = $db->real_escape_string($search_query); // Use your database object's escape function

// Build the SQL query using a placeholder
$sql = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $safe_query . "%'";

// Prepare the statement
$stmt = $db->prepare($sql);

// Bind the parameter
$stmt->bind_param("s", $safe_query); // "s" indicates a string parameter

// Execute the statement
$result = $stmt->execute();

// Fetch the results
if ($result) {
    // ... (Display the results as before)
} else {
    // Handle errors
}

// Close the statement
$stmt->close();
?>
