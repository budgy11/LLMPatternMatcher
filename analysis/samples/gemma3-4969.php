
</body>
</html>


<?php

// Sample data (replace this with your actual data source)
$items = [
    "apple",
    "banana",
    "orange",
    "grapefruit",
    "kiwi",
    "strawberry",
    "watermelon",
    "pineapple",
    "mango",
    "pear",
];

// Check if the search form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the search term from the form
    $searchTerm = isset($_POST["search_term"]) ? trim($_POST["search_term"]) : "";

    // Sanitize the search term to prevent XSS attacks
    $searchTerm = htmlspecialchars($searchTerm);

    // Perform the search
    if ($searchTerm != "") {
        $searchResults = [];
        foreach ($items as $item) {
            if (stripos($item, $searchTerm) !== false) {  // Use stripos for case-insensitive search
                $searchResults[] = $item;
            }
        }

        // Display the results
        if (count($searchResults) > 0) {
            echo "<h2>Search Results for: " . $searchTerm . "</h2>";
            echo "<ul>";
            foreach ($searchResults as $result) {
                echo "<li>" . $result . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No results found for: " . $searchTerm . "</p>";
        }
    }
}
?>
