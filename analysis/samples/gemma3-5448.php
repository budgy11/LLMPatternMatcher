    <input type="text" name="search_term" placeholder="Search..." value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>">
    <button type="submit">Search</button>
  </form>
</div>

<?php
// Example data (replace with your actual data source)
$data = [
  "apple", "banana", "cherry", "date", "elderberry",
  "fig", "grape", "honeydew", "kiwi", "lemon"
];

if (isset($_GET['search_term'])) {
  $searchTerm = htmlspecialchars($_GET['search_term']);
  $searchResults = [];

  foreach ($data as $item) {
    if (strpos($item, $searchTerm) !== false) {
      $searchResults[] = $item;
    }
  }

  if (!empty($searchResults)) {
    echo "<h2>Search Results:</h2>";
    echo "<ul>";
    foreach ($searchResults as $result) {
      echo "<li>" . $result . "</li>";
    }
    echo "</ul>";
  } else {
    echo "<p>No results found for '" . $searchTerm . "'.</p>";
  }
}
?>
