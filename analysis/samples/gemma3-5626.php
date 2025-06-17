        <input type="text" name="search" placeholder="Enter search term" value="<?php echo $searchTerm; ?>">
        <button type="submit">Search</button>
    </form>

    <?php
    if ($result->num_rows > 0) {
        echo "<br><br>"; // Add a newline for better readability
        echo "<ul>";
        // Output each row as a list item
        while($row = $result->fetch_assoc()) {
            echo "<li>" . $row["your_column_name"] . "</li>"; // Adjust this based on your table structure
        }
        echo "</ul>";
    } else {
        echo "<p>No results found.</p>";
    }
    ?>
