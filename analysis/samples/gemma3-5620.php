    <input type="text" name="search" value="<?php echo $searchTerm; ?>" placeholder="Enter search term...">
    <button type="submit">Search</button>
  </form>

  <?php
  // Example:  Search through a simple array of data
  $data = [
    ['name' => 'Apple', 'description' => 'A delicious fruit'],
    ['name' => 'Banana', 'description' => 'A yellow fruit'],
    ['name' => 'Orange', 'description' => 'A citrus fruit'],
    ['name' => 'Grape', 'description' => 'Small and sweet fruit']
  ];

  if ($searchTerm) {
    echo "<h2>Search Results for: " . $searchTerm . "</h2>";
    echo "<ul>";
    foreach ($data as $item) {
      // Search within name and description (case-insensitive)
      $nameMatch = stripos($item['name'], $searchTerm) !== false;
      $descriptionMatch = stripos($item['description'], $searchTerm) !== false;

      if ($nameMatch || $descriptionMatch) {
        echo "<li>";
        echo htmlspecialchars($item['name']); // Escape output for security
        if ($descriptionMatch) {
          echo "<br>". htmlspecialchars($item['description']);
        }
        echo "</li>";
      }
    }
    echo "</ul>";
  }
?>
