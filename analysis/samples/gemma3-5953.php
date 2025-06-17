  <input type="text" name="search_term" placeholder="Enter search term...">
  <button type="submit">Search</button>
</form>


function performSearch($search_term) {
    $items = array(
        "apple",
        "banana",
        "orange",
        "grapefruit",
        "mango"
    );

    $results = array();
    foreach ($items as $item) {
        if (stripos($item, $search_term)) {  // Case-insensitive search
            $results[] = $item;
        }
    }
    return $results;
}


// Assuming you have a database connection established in $db

function performSearch($search_term) {
  $query = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'"; // Use LIKE for partial matches
  $result = $db->query($query);

  $results = array();
  while ($row = $result->fetch_assoc()) {
    $results[] = $row['name']; // Or whatever column you want to display
  }
  return $results;
}


<?php

// Simple search bar implementation in PHP

// --- Configuration ---
$search_term = ""; // Default search term
$results = [];       // Array to store search results
$data = [  // Sample data - Replace with your actual data source
    ['name' => 'Apple', 'category' => 'Fruit', 'description' => 'A red fruit'],
    ['name' => 'Banana', 'category' => 'Fruit', 'description' => 'A yellow fruit'],
    ['name' => 'Computer', 'category' => 'Electronics', 'description' => 'A device for processing data'],
    ['name' => 'Laptop', 'category' => 'Electronics', 'description' => 'A portable computer'],
    ['name' => 'Book', 'category' => 'Books', 'description' => 'A written or printed work'],
];

// --- Search Function ---
function search_data($searchTerm, $data) {
    $results = [];
    foreach ($data as $item) {
        // Case-insensitive search
        $title = strtolower($item['name']);
        $description = strtolower($item['description']);

        // Check if the search term is in the title or description
        if (strpos($title, $searchTerm) !== false || strpos($description, $searchTerm) !== false) {
            $results[] = $item;
        }
    }
    return $results;
}

// --- Handle Search Input (Example - Simple form submission) ---
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Check if a search term was submitted
    if (isset($_GET["search_term"])) {
        $search_term = htmlspecialchars($_GET["search_term"]);  // Sanitize input
    }
}

// --- Execute Search ---
$results = search_data($search_term, $data);

// --- Display Search Bar and Results ---
?>
