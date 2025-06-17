    <input type="text" name="search_term" placeholder="Enter search term...">
    <button type="submit">Search</button>
  </form>

</body>
</html>


// Assuming you have a database connection established as $conn

$search_term = trim($_POST["search_term"]);
$search_term = htmlspecialchars($search_term);

// Example SQL query (adapt to your table and column names)
$sql = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // Output data of each row
  while($row = $result->fetch_assoc()) {
    // Display product details
    echo "Product Name: " . htmlspecialchars($row["name"]) . "<br>";
    echo "Description: " . htmlspecialchars($row["description"]) . "<br>";
    echo "<br>";
  }
} else {
  echo "<p>No results found for: " . $search_term . "</p>";
}


<?php

// Sample data (replace with your actual data source)
$products = [
    'Laptop 1' => 'Dell XPS 13',
    'Laptop 2' => 'MacBook Air',
    'Keyboard' => 'Logitech K380',
    'Mouse' => 'Logitech MX Master 3',
    'Monitor' => 'LG 27UL850',
];


// Function to handle the search
function searchProducts($search_term, $products) {
    $results = [];

    // Normalize the search term (lowercase and remove whitespace)
    $search_term = strtolower(trim($search_term));

    foreach ($products as $key => $value) {
        // Normalize the product name as well (for case-insensitive search)
        $product_name = strtolower(trim($value));

        // Check if the search term is present in the product name
        if (strpos($product_name, $search_term) !== false) {
            $results[$key] = $value;
        }
    }

    return $results;
}

// Get the search term from the form (or URL parameter)
$search_term = isset($_GET['search']) ? $_GET['search'] : '';  // Check if the 'search' parameter is set


// Perform the search
$search_results = searchProducts($search_term, $products);


?>
