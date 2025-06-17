

<?php
// Assume you have a database connection established and a variable $conn

// Get the search term
$search_term = isset($_POST['search_term']) ? $_POST['search_term'] : '';

// Search in the 'products' table
$query = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%' OR description LIKE '%" . $search_term . "%'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo "<h2>Search Results:</h2>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div>";
        echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
        echo "<p>" . htmlspecialchars($row['description']) . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>No results found for '" . htmlspecialchars($search_term) . "'.</p>";
}

// ... (rest of the HTML form and database connection setup) ...
?>
