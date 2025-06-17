        <input type="text" name="search" placeholder="Enter search term" value="<?php echo $searchTerm; ?>">
        <input type="submit" value="Search">
    </form>

    <?php
    if (!empty($searchTerm)) {
        if ($results && $results->num_rows > 0) {
            echo "<h2>Search Results for: " . htmlspecialchars($searchTerm) . "</h2>";
            echo "<ul>";
            while ($row = $results->fetch_assoc()) {
                echo "<li>";
                echo "<strong>" . htmlspecialchars($row['title']) . "</strong> - " . htmlspecialchars($row['description']) . "</li>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No results found for '" . htmlspecialchars($searchTerm) . "'.</p>";
        }
    } else {
        // Display all results (if no search term)
        if ($result->num_rows > 0) {
            echo "<h2>All Results</h2>";
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li>";
                echo "<strong>" . htmlspecialchars($row['title']) . "</strong> - " . htmlspecialchars($row['description']) . "</li>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No results found.</p>";
        }
    }

    // Close the database connection
    $conn->close();
    ?>
