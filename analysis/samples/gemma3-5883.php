        <input type="text" name="search_term" placeholder="Enter search term">
        <button type="submit">Search</button>
    </form>

    <?php if (empty($searchResults)) {
        echo "<p>No results found.</p>";
    } else {
        echo "<ul>";
        foreach ($searchResults as $result) {
            echo "<li>ID: " . $result['id'] . ", Name: " . $result['name'] . ", Description: " . $result['description'] . "</li>";
        }
        echo "</ul>";
    }
?>
