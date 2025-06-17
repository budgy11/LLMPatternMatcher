

<?php

// Include the necessary libraries
require_once 'vendor/autoload.php'; // Assuming you're using Composer for dependencies

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DomCrawler\Crawler;

// Sample data (replace with your actual data source - database, file, etc.)
$data = [
    'apple',
    'banana',
    'orange',
    'grape',
    'watermelon',
    'pineapple',
    'strawberry',
    'mango',
    'kiwi',
    'pear'
];

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchTerm = trim($_POST["search_term"]); // Get the search term from the form

    if (empty($searchTerm)) {
        $results = []; // Empty results if search term is empty
    } else {
        $results = [];
        foreach ($data as $item) {
            if (stripos($item, $searchTerm) !== false) { // Case-insensitive search using stripos
                $results[] = $item;
            }
        }
    }
} else {
    // If not a post request, initialize empty results
    $results = [];
}

?>
