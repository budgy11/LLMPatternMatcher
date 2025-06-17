

<?php
// Assuming $db is your established database connection
// This script will handle displaying search results

// Get the search term from the URL (query string)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($searchTerm)) {
    // Execute the search query (same query as the main search page)
    $query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
    $result = mysqli_query($db, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo "<h3>Search Results for: " . $searchTerm . "</h3>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div>";
                echo "<strong>" . $row['name'] . "</strong> - " . $row['description'] . "<br>";
                echo "<a href='product_details.php?id=" . $row['id'] . "'>View Details</a><br>";
                echo "</div>";
            }
        } else {
            echo "<p>No products found matching your search.</p>";
        }
        mysqli_free_result($result);
    } else {
        echo "<p>Error executing the query.</p>";
        echo mysqli_error($db);
    }
}
?>
