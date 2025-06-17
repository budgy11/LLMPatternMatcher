  <input type="text" name="search_term" placeholder="Search..." value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>">
  <button type="submit">Search</button>
</form>

<?php

// Example:  Echo the search term if it exists (for demonstration)
if (isset($_GET['search_term'])) {
  echo "<p>You searched for: " . htmlspecialchars($_GET['search_term']) . "</p>";
}

// **IMPORTANT:  Replace this with your actual database query and logic**
// This is just a placeholder to show how to use the search term.

// Example:  (For illustrative purposes, assuming a simple database)
/*
$search_term = isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : '';

// Replace this with your database connection and query.
//  This is just a simplified example.

$results = [];
// Example data (replace with your database query)
$products = [
  "Red T-shirt",
  "Blue Jeans",
  "Black Shoes",
  "Green Hat"
];
foreach ($products as $product) {
    if (strpos($product, $search_term) !== false) {
        $results[] = $product;
    }
}

if (count($results)) {
    echo "<p>Search Results:</p>";
    echo "<ul>";
    foreach ($results as $result) {
        echo "<li>" . htmlspecialchars($result) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No results found for '" . htmlspecialchars($search_term) . "'.</p>";
}
*/

?>
