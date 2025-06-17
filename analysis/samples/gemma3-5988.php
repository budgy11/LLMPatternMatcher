

<?php

// This code creates a simple search bar and handles the search functionality.

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the search term from the form
    $searchTerm = $_POST["search_term"];

    // Sanitize the search term (important for security)
    $searchTerm = htmlspecialchars(trim($searchTerm));

    // Perform the search (replace with your actual search logic)
    $searchResults = searchDatabase($searchTerm); // See function definition below

    // If a search was performed, display the results
    if ($searchResults) {
        echo "<h2>Search Results for: " . $searchTerm . "</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Title</th><th>Description</th></tr>";
        foreach ($searchResults as $result) {
            echo "<tr>";
            echo "<td>" . $result["title"] . "</td>";
            echo "<td>" . $result["description"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No results found for '" . $searchTerm . "'.</p>";
    }
}
?>
