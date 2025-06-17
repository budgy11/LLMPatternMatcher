    <input type="text" name="search_term" placeholder="Enter search term">
    <input type="submit" value="Search">
  </form>

</body>
</html>


<?php

// Define the search term (for demonstration purposes - ideally, this would come from a form)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term to prevent XSS vulnerabilities
$searchTerm = htmlspecialchars($searchTerm);

// Handle the search request
if ($searchTerm) {
    // Perform your search logic here. This is just a placeholder.
    // You would likely query a database or search through an array.
    $searchResults = ['apple', 'banana', 'orange', 'grape', 'watermelon'];

    // Filter the results based on the search term
    $filteredResults = array_filter($searchResults, function($item) use ($searchTerm) {
        return stripos($item, $searchTerm) !== false; // case-insensitive search
    });

    // Display the results
    echo '<h2>Search Results for: ' . $searchTerm . '</h2>';
    if (empty($filteredResults)) {
        echo '<p>No results found for: ' . $searchTerm . '</p>';
    } else {
        echo '<ul>';
        foreach ($filteredResults as $result) {
            echo '<li>' . $result . '</li>';
        }
        echo '</ul>';
    }

} else {
    // Display a form to enter the search term
    echo '<form action="" method="get">';
    echo '<input type="text" name="search" value="' . htmlspecialchars($searchTerm) . '" placeholder="Enter search term">';
    echo '<button type="submit">Search</button>';
    echo '</form>';
}
?>
