

<?php

// Basic search bar implementation with simple string comparison

// --- Configuration ---
$search_term = isset($_GET['search']) ? $_GET['search'] : ''; // Get the search term from the GET request
$results = []; // Initialize an empty array to hold the search results (replace with your actual data source)

// --- Data (Example Data - Replace with your database or other data source) ---
$data = [
    ['id' => 1, 'name' => 'Apple', 'description' => 'A sweet fruit'],
    ['id' => 2, 'name' => 'Banana', 'description' => 'A yellow fruit'],
    ['id' => 3, 'name' => 'Orange', 'description' => 'A citrus fruit'],
    ['id' => 4, 'name' => 'Grape', 'description' => 'Small, juicy fruit'],
];

// --- Search Logic ---
if ($search_term) {
    $search_term = trim($search_term); // Remove leading/trailing whitespace
    foreach ($data as $item) {
        $name = trim($item['name']);
        $description = trim($item['description']);

        if (strpos(strtolower($name), $search_term) !== false || strpos(strtolower($description), $search_term) !== false) {
            $results[] = $item;
        }
    }
}

// --- HTML Output ---
echo '<form method="get" action="">
        <input type="text" name="search" value="' . htmlspecialchars($search_term) . '" placeholder="Search...">
        <button type="submit">Search</button>
    </form>';

if (!empty($results)) {
    echo '<h2>Search Results:</h2>';
    echo '<ul>';
    foreach ($results as $result) {
        echo '<li>ID: ' . $result['id'] . ' - ' . $result['name'] . ' - ' . $result['description'] . '</li>';
    }
    echo '</ul>';
} else {
    echo '<p>No results found for "' . htmlspecialchars($search_term) . '".</p>';
}

?>
