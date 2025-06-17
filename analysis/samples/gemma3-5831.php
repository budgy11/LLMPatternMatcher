

<?php

// Check if the search form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the search term from the form
    $searchTerm = htmlspecialchars($_POST["search_term"]);

    // Sanitize the search term (important for security)
    $searchTerm = trim($searchTerm);

    // Perform the search (example: simple string search)
    if ($searchTerm != "") {
        $results = array();
        // Example:  Search through an array of items
        $items = array(
            "apple", "banana", "cherry", "date", "elderberry", "fig", "grape"
        );

        foreach ($items as $item) {
            if (stripos($item, $searchTerm) !== false) { // Case-insensitive search
                $results[] = $item;
            }
        }


        // Display the results
        echo "<ul>";
        foreach ($results as $result) {
            echo "<li>" . $result . "</li>";
        }
        echo "</ul>";

    } else {
        echo "<p>Please enter a search term.</p>";
    }
}
?>
