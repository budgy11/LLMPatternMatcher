        <input type="text" name="search_term" value="<?php echo htmlspecialchars($searchTerm); ?>" />
        <input type="submit" value="Search" />
    </form>

    <?php
    // Display the search results
    if (!empty($searchResults)) {
        echo "<h2>Search Results:</h2>";
        echo "<ul>";
        foreach ($searchResults as $result) {
            echo "<li>" . htmlspecialchars($result) . "</li>"; //Escape for security
        }
        echo "</ul>";
    } else {
        echo "<p>No results found for '" . htmlspecialchars($searchTerm) . "'.</p>";
    }
    ?>
