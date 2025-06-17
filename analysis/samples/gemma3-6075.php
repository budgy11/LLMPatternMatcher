
</body>
</html>


<?php
// Assuming you have a database connection established in a separate file (e.g., $db)
// and that you've executed a query to fetch the data.

// Example:
// $data = $db->query("SELECT name, description FROM products WHERE name LIKE '%" . $searchTerm . "%'")->fetchAll(PDO::FETCH_ASSOC);

$searchTerm = isset($_GET['search_term']) ? $_GET['search_term'] : '';

// Perform the search (similar logic as before, but using the fetched data)
$results = [];
if (!empty($searchTerm)) {
    // Replace this with your database query to fetch the results
    $results = $db->query("SELECT name, description FROM products WHERE name LIKE '%" . $searchTerm . "%' OR description LIKE '%" . $searchTerm . "%'")->fetchAll(PDO::FETCH_ASSOC);
}

// Display the results
if (!empty($results)) {
    echo "<h2>Search Results:</h2>";
    echo "<ul>";
    foreach ($results as $result) {
        echo "<li>Name: " . $result['name'] . ", Description: " . $result['description'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No results found for '" . $searchTerm . "'.</p>";
}
?>
