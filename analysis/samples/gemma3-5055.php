        <input type="submit" value="Search">
    </form>

    <?php
    if ($results) {
        echo "<pre>"; // Use <pre> for better formatting of the results
        while ($row = $results->fetch_assoc()) {
            echo print_r($row, true); // For detailed output - remove 'true' for cleaner output
            echo "<br>";
        }
        echo "</pre>";
    } else {
        echo "<p>No results found for '" . htmlspecialchars($search_term) . "'</p>";
    }
    ?>
