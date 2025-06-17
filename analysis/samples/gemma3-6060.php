        <button type="submit">Search</button>
    </form>

    <?php if (empty($results)) {
        echo "<p>No results found for '" . $searchTerm . "'</p>";
    } else {
        echo "<ul>";
        foreach ($results as $result) {
            echo "<li>" . $result . "</li>";
        }
        echo "</ul>";
    } ?>
