

<?php

// Assuming you have a database connection established (e.g., mysqli)
// Replace 'your_db_connection' with your actual connection variable
// Example: $conn = mysqli_connect("localhost", "username", "password", "database_name");

// Get the search term from the GET request (e.g., if you have a URL like: ?search=keyword)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term - Important for security!
$searchTerm = htmlspecialchars(trim($searchTerm));

// Database connection (replace with your connection details)
// Example: $conn = mysqli_connect("localhost", "username", "password", "database_name");

// Table name to search in (replace with your actual table name)
$tableName = 'your_table_name';

// Column to search in (replace with the column you want to search)
$searchColumn = 'your_column_name';

// Prepare the SQL query
$query = "SELECT * FROM $tableName WHERE $searchColumn LIKE '%$searchTerm%'";

// Execute the query
// Example using mysqli
// $result = mysqli_query($conn, $query);

// **Important Security Considerations:**

// 1. **Prepared Statements (Strongly Recommended):**  The above code is vulnerable to SQL injection. Always use prepared statements to prevent this.  Prepared statements separate the SQL code from the user-supplied data, preventing malicious code from being executed.  I'll provide a complete example using prepared statements below.

// 2. **Sanitization:**  `htmlspecialchars()` is used to escape characters that could be interpreted as HTML or SQL code.  This is a good practice but doesn't fully protect against all injection attacks.

// 3. **Case Sensitivity:** `LIKE '%$searchTerm%'` is case-insensitive in many databases. If you need case-sensitive searching, use `BINARY LIKE` (MySQL) or equivalent for your database.

// **Example using Prepared Statements (Highly Recommended):**

// Assuming a $conn mysqli connection variable exists.
// If not, you must establish a connection using mysqli_connect().

// Prepare the statement
// $stmt = mysqli_prepare($conn, "SELECT * FROM $tableName WHERE $searchColumn LIKE ?");

// Bind the parameter (the search term)
// mysqli_stmt_bind_param($stmt, "s", $searchTerm);

// Execute the statement
// mysqli_stmt_execute($stmt);

// Get the results
// $result = mysqli_stmt_get_result($stmt); // or mysqli_stmt_use_result($stmt);

// Output the search results
// while ($row = mysqli_fetch_assoc($result)) {
//   echo "ID: " . $row['id'] . "<br>"; // Assuming your table has an 'id' column
//   echo "Name: " . $row['name'] . "<br>";
//   // ... other columns ...
//   echo "<br>";
// }

// Close the result set
// mysqli_free_result($result);

// Close the statement
// mysqli_close($stmt);



// **Complete Example with Prepared Statements and HTML Output:**

?>
