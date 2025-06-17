        <input type="text" name="search" placeholder="Enter search term">
        <button type="submit">Search</button>
    </form>

    <?php
    if ($result->num_rows > 0) {
        echo "<ul>";
        // Output each row of the result
        while ($row = $result->fetch_assoc()) {
            echo "<li>";
            // Display the columns you want to show. Replace with your table columns.
            echo "<strong>" . htmlspecialchars($row["your_column_name_1"]) . "</strong>: " . htmlspecialchars($row["your_column_name_2"]) . "<br>";
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No results found.</p>";
    }
    ?>
