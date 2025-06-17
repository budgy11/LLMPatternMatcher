        <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <input type="submit" value="Search">
    </form>

    <?php
    if (mysqli_num_rows($result) > 0) {
        echo "<ul>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li>" . htmlspecialchars($row['your_column_name']) . "</li>"; // Replace your_column_name
        }
        echo "</ul>";
    } else {
        echo "<p>No results found.</p>";
    }
    ?>
