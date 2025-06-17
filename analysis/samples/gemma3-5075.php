    <button type="submit">Search</button>
  </form>
  <?php
}


// Example data (replace with your actual data source)
$data = [
  "apple",
  "banana",
  "cherry",
  "date",
  "fig",
  "grape",
  "orange",
  "pineapple",
  "strawberry"
];


// Handle the search submission
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  $search_term = isset($_GET['search_term']) ? trim($_GET['search_term']) : '';

  if (!empty($search_term)) {
    // Perform the search
    $results = [];
    foreach ($data as $item) {
      if (stripos($item, $search_term) !== false) { // Case-insensitive search
        $results[] = $item;
      }
    }

    // Display the results
    if (!empty($results)) {
      echo "<h2>Search Results:</h2>";
      echo "<ul>";
      foreach ($results as $result) {
        echo "<li>" . htmlspecialchars($result) . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found for '" . htmlspecialchars($search_term) . "'.</p>";
    }
  }
}
?>
