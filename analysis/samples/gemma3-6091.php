
</body>
</html>


<?php
// Assuming you have a database connection established as $conn

// Get the search term from the query string
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term
$search_term = htmlspecialchars($search_term);

// Construct the database query
$query = "SELECT product_name, description FROM products WHERE product_name LIKE '%" . $search_term . "%' OR description LIKE '%" . $search_term . "%'";

// Execute the query
$result = mysqli_query($conn, $query);

$results = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $results[$row['product_name']] = $row['description'];
    }
    mysqli_free_result($result); // Important: Free the result set
} else {
    // Handle database error
    echo "Error: " . mysqli_error($conn);
}

// ... (rest of the HTML code as above) ...
?>
