        <input type="text" name="search_term" placeholder="Enter search term">
        <input type="submit" value="Search">
    </form>

</body>
</html>


<?php

// Sample data (replace with your actual data source)
$products = [
    "Laptop" => "Apple MacBook Pro 16\"",
    "Smartphone" => "Samsung Galaxy S23 Ultra",
    "Headphones" => "Sony WH-1000XM5",
    "Keyboard" => "Logitech MX Keys",
    "Mouse" => "Logitech MX Master 3"
];

// Function to handle the search
function searchProducts($searchTerm, $products) {
    $results = [];

    foreach ($products as $key => $value) {
        if (strtolower($key) == strtolower($searchTerm)) {
            $results[$key] = $value;
        } elseif (strpos($value, strtolower($searchTerm)) !== false) {
            $results[$key] = $value;
        }
    }

    return $results;
}

// Get the search term from the user (e.g., from a form)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : ''; // Get from URL

// Perform the search
$searchResults = searchProducts($searchTerm, $products);

?>
