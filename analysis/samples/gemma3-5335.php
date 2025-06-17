        <button type="submit">Search</button>
    </form>

    <?php
    if ($result->num_rows > 0) {
        echo "<ul>";
        // Output the results
        while($row = $result->fetch_assoc()) {
            echo "<li>" . $row['name'] . "</li>";  // Replace 'name' with the actual column name
        }
        echo "</ul>";
    } else {
        echo "<p>No results found.</p>";
    }
    ?>
