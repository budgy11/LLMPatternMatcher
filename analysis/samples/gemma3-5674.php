
</body>
</html>


<?php

// Sample data for demonstration purposes
$products = [
    'Laptop - Dell XPS 13' => 'Description for Dell XPS 13',
    'Mouse - Logitech MX Master 3' => 'Description for Logitech MX Master 3',
    'Keyboard - Corsair K95 RGB' => 'Description for Corsair K95 RGB',
    'Monitor - LG 27UL850-W' => 'Description for LG 27UL850-W',
    'Webcam - Logitech Brio 4K' => 'Description for Logitech Brio 4K',
];

// Get the search term from the GET request (if provided)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent XSS vulnerabilities
$searchTerm = htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8');

// Perform the search
$searchResults = [];
foreach ($products as $key => $value) {
    // Case-insensitive search
    if (stripos($key, $searchTerm) !== false) {
        $searchResults[$key] = $value;
    }
}

?>
