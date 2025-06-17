
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// and you want to search a table named 'products' with a column named 'name'

// Get the search term from the GET request (if provided)
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent SQL injection and XSS attacks
$search_term = htmlspecialchars(trim($search_term));

// Validate the search term -  important for security and usability
if (empty($search_term)) {
    $results = []; // Return an empty array if no search term is provided
} else {
    // Prepare the SQL query -  VERY IMPORTANT for security
    $sql = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'";

    // Execute the query
    $result = mysqli_query($db, $sql);

    if ($result) {
        $results = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
        }
    } else {
        // Handle query error
        echo "Error: " . mysqli_error($db);
    }
}

?>
