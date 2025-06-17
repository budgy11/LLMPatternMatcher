

<?php

// Sample data (replace with your actual data source - database, array, etc.)
$data = [
    "apple",
    "banana",
    "orange",
    "grapefruit",
    "melon",
    "pear",
    "strawberry",
    "mango",
    "pineapple",
    "cherry"
];

// Handle the search form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchTerm = trim($_POST["search_term"]);

    if (!empty($searchTerm)) {
        $results = [];
        foreach ($data as $item) {
            if (stripos($item, $searchTerm) !== false) { // Case-insensitive search
                $results[] = $item;
            }
        }

        if (!empty($results)) {
            echo "<ul>";
            foreach ($results as $result) {
                echo "<li>" . $result . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No results found for '" . $searchTerm . "'.</p>";
        }
    } else {
        echo "<p>Please enter a search term.</p>";
    }
}
?>
