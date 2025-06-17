    <input type="text" name="search_term" placeholder="Search..." value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>">
    <button type="submit">Search</button>
  </form>
</div>

<?php
// Example data to search through (replace with your actual data)
$data = [
  "apple", "banana", "orange", "grapefruit", "kiwi",
  "watermelon", "pineapple", "strawberry", "blueberry", "raspberry"
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
    echo "<pre>"; // For better formatting of the results
    print_r($searchResults);
    echo "</pre>";
  } else {
    echo "<p>No results found for: " . $searchTerm . "</p>";
  }
}
?>
