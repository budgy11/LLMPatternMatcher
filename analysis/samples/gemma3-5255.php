        <input type="text" name="search_term" value="<?php echo htmlspecialchars($search_term); ?>" placeholder="Enter product name">
        <button type="submit">Search</button>
    </form>

    <?php
    if ($result->num_rows > 0) {
        echo "<h2>Search Results:</h2>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($row["name"]) . " - " . htmlspecialchars($row["description"]) . "</li>";  // Customize what you display.
        }
        echo "</ul>";
    } else {
        echo "<p>No products found matching your search term.</p>";
    }
    ?>
