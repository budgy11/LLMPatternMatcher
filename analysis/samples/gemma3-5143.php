    <input type="text" name="search_term" placeholder="Enter product name">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<?php

// Simple search bar implementation

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the search term from the form
    $searchTerm = isset($_POST["search_term"]) ? htmlspecialchars($_POST["search_term"]) : '';

    // Perform the search (replace with your actual search logic)
    if ($searchTerm) {
        $results = searchData($searchTerm); // Call your search function

        // Display the results
        echo "<h2>Search Results for: " . htmlspecialchars($searchTerm) . "</h2>";
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
}

?>
