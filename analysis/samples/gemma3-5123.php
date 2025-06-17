        <button type="submit">Search</button>
    </form>

    <?php if (empty($searchResults)) {
        echo "<p>No results found for '" . htmlspecialchars($searchTerm) . "'</p>";
    } else {
        echo "<h2>Search Results:</h2>";
        echo "<ul>";
        foreach ($searchResults as $result) {
            echo "<li>ID: " . $result['id'] . ", Name: " . htmlspecialchars($result['name']) . "</li>";
        }
        echo "</ul>";
    } ?>
