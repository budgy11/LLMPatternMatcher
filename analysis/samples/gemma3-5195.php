
</body>
</html>


function searchDatabase($search_term, $table_name) {
  // Example using PDO and prepared statements
  try {
    $db = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

    $stmt = $db->prepare("SELECT * FROM $table_name WHERE name LIKE :search OR description LIKE :search");
    $stmt->bindParam(':search', '%' . $search_term . '%'); // Using LIKE with wildcard for partial matches
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $results;
  } catch (PDOException $e) {
    // Handle the database error (e.g., log the error, display an error message)
    error_log("Database error: " . $e->getMessage()); // Log the error
    return []; // Return an empty array in case of error
  }
}


<?php

// Sample data (replace with your actual data source - database, file, etc.)
$items = [
    "apple",
    "banana",
    "orange",
    "grape",
    "watermelon",
    "pear",
    "strawberry",
    "kiwi",
    "mango",
];

// Function to handle the search
function searchItems($search_term, $items) {
    $results = [];

    // Convert search term to lowercase for case-insensitive search
    $search_term = strtolower($search_term);

    foreach ($items as $item) {
        // Convert item to lowercase for case-insensitive search
        $item_lower = strtolower($item);

        // Check if the search term is a substring of the item
        if (strpos($item_lower, $search_term) !== false) {
            $results[] = $item;
        }
    }

    return $results;
}

// Get the search term from the form (or URL parameter)
$search_term = isset($_GET['search']) ? $_GET['search'] : ''; 

// Perform the search
$search_results = searchItems($search_term, $items);

?>
