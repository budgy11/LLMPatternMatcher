
</body>
</html>


$stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
$stmt->bind_param("s", "%" . $search_term . "%", "%" . $search_term . "%");
$stmt->execute();
$result = $stmt->get_result();
// ... process $result ...
$stmt->close();


<?php

// Sample data (replace with your actual data source - database, array, etc.)
$items = [
    "apple",
    "banana",
    "orange",
    "grape",
    "watermelon",
    "strawberry",
    "pineapple",
    "kiwi"
];

// Get the search term from the GET request
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars($searchTerm);

// Perform the search
$searchResults = [];
if ($searchTerm) {
    foreach ($items as $item) {
        // Case-insensitive comparison
        if (stripos($item, $searchTerm) !== false) {
            $searchResults[] = $item;
        }
    }
}

?>
