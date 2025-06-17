        <input type="submit" value="Search">
    </form>

    <?php
    // Display the search results
    if (!empty($results)) {
        echo "<h2>Search Results:</h2>";
        echo "<ul>";
        foreach ($results as $product) {
            echo "<li>" . $product['name'] . " - " . $product['description'] . "</li>"; // Assuming 'name' and 'description' columns exist
        }
        echo "</ul>";
    } else {
        echo "<p>No products found matching your search.</p>";
    }
    ?>
