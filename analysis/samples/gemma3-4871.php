  <button type="submit">Search</button>
</form>


<?php

// Assuming you have a database connection established ($conn)

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security!)
$searchTerm = htmlspecialchars(trim($searchTerm));

// Escape the search term for use in the SQL query (prevent SQL injection)
$searchTerm = $conn->real_escape_string($searchTerm);


// Build the SQL query
$sql = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $searchTerm . "%'";  // Replace 'your_table_name' and 'your_column_name'

// Execute the query
$result = $conn->query($sql);

?>
