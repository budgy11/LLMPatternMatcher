        <input type="text" name="search" value="<?php echo $searchTerm; ?>" placeholder="Enter search term">
        <button type="submit">Search</button>
    </form>

    <?php
    // Display the search results
    if (mysqli_num_rows($result) > 0) {
        echo "<ul>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li>" . htmlspecialchars($row['column_to_search']) . "</li>"; // Replace 'column_to_search' with the actual column name
        }
        echo "</ul>";
    } else {
        echo "<p>No results found.</p>";
    }

    // Close the database connection
    mysqli_close($connection);
    ?>
