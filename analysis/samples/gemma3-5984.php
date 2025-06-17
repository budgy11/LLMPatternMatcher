

</body>
</html>


<?php

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the search term from the form
    $searchTerm = $_POST["search_term"];

    // Sanitize the search term (important for security!)
    $searchTerm = htmlspecialchars($searchTerm); // Prevents XSS vulnerabilities

    // Perform the search (replace with your database query or logic)
    $results = performSearch($searchTerm); 

    // Display the results
    echo "<h2>Search Results for: " . $searchTerm . "</h2>";
    if (empty($results)) {
        echo "<p>No results found.</p>";
    } else {
        echo "<ul>";
        foreach ($results as $result) {
            echo "<li>" . $result . "</li>";
        }
        echo "</ul>";
    }
}
?>
