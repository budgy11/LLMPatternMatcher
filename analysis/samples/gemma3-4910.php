
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Replace 'database_connection' with your actual connection object.
// For demonstration purposes, we'll use a simple hardcoded array.
$items = [
    'apple',
    'banana',
    'orange',
    'grape',
    'watermelon',
    'pear',
    'mango',
];

// If you're using a database, you'd query here instead:
// $results = mysqli_query($connection, "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'");

// Get the search term from the form
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important to prevent SQL injection and XSS)
$search_term = htmlspecialchars(trim($search_term));

// Perform the search
$results = [];
if ($search_term) {
    foreach ($items as $item) {
        if (stripos($item, $search_term)) { // Case-insensitive search
            $results[] = $item;
        }
    }
}

?>
