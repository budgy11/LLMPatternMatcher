        <input type="text" name="search" placeholder="Enter product name..." value="<?php echo $searchTerm; ?>">
        <button type="submit">Search</button>
    </form>

    <?php
    if ($result->num_rows > 0) {
        echo "<h2>Search Results:</h2>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . $row['name'] . " - " . $row['description'] . "</li>"; // Assuming 'description' column
        }
        echo "</ul>";
    } else {
        echo "<p>No products found matching your search.</p>";
    }
    ?>
