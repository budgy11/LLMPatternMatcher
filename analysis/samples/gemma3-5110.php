    <button type="submit">Search</button>
</form>

<?php

// **PHP Code to Handle the Search**

// Check if the search form has been submitted
if (isset($_GET['search'])) {
    // Get the search term
    $searchTerm = $_GET['search'];

    // Sanitize the search term to prevent XSS attacks. IMPORTANT!
    $searchTerm = htmlspecialchars($searchTerm);

    //  Example: Search in an array of strings.  Replace this with your actual data source.
    $data = array(
        "apple",
        "banana",
        "orange",
        "grapefruit",
        "strawberry"
    );

    // Convert the search term to lowercase for case-insensitive search
    $searchTerm = strtolower($searchTerm);

    // Search the data
    $searchResults = [];
    foreach ($data as $item) {
        if (strpos(strtolower($item), $searchTerm) !== false) {
            $searchResults[] = $item;
        }
    }

    // Display the search results (or a "No results found" message)
    if (empty($searchResults)) {
        echo "<p>No results found for '" . $searchTerm . "'</p>";
    } else {
        echo "<h2>Search Results for '" . $searchTerm . "'</h2>";
        echo "<ul>";
        foreach ($searchResults as $result) {
            echo "<li>" . $result . "</li>";
        }
        echo "</ul>";
    }
}
?>
