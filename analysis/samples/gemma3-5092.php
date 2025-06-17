
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)

// Get the search term from the form
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent SQL injection
$search_term = htmlspecialchars(trim($search_term));

// Escape characters for use in SQL (important for security)
$search_term = $db->real_escape_string($search_term);

// SQL query construction
$sql = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $search_term . "%'";

// Execute the query
$result = $db->query($sql);

?>
