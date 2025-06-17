  <input type="text" name="search" placeholder="Search..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
  <button type="submit">Search</button>
</form>

<?php

// --- Search Logic ---

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = htmlspecialchars($_GET['search']);

    // Perform the search
    $searchResults = [];
    foreach ($data as $item) {
        // Case-insensitive search on name AND description
        $name = strtolower($item['name']);
        $description = strtolower($item['description']);
        if (strpos($name, $searchTerm) !== false || strpos($description, $searchTerm) !== false) {
            $searchResults[] = $item;
        }
    }

    // Display the search results
    if (!empty($searchResults)) {
        echo "<h2>Search Results:</h2>";
        echo "<ul>";
        foreach ($searchResults as $result) {
            echo "<li>ID: " . $result['id'] . ", Name: " . $result['name'] . ", Description: " . $result['description'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No results found for '" . $searchTerm . "'.</p>";
    }
} else {
  // If no search term is provided, display the original data
  echo "<h2>Data:</h2>";
  echo "<ul>";
  foreach ($data as $item) {
      echo "<li>ID: " . $item['id'] . ", Name: " . $item['name'] . ", Description: " . $item['description'] . "</li>";
  }
  echo "</ul>";
}


?>
