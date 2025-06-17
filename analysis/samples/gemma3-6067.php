    <button type="submit">Search</button>
  </form>

  <?php
    // Example of how to search in a simple array
    $items = array(
      "apple", "banana", "cherry", "date", "elderberry", "fig"
    );

    if ($searchTerm) {
      echo "<h2>Search Results:</h2>";
      echo "<ul>";
      foreach ($items as $item) {
        $lowerCaseItem = strtolower($item);
        $lowerCaseSearchTerm = strtolower($searchTerm);
        if (strpos($lowerCaseItem, $lowerCaseSearchTerm) !== false) {
          echo "<li>" . $item . "</li>";
        }
      }
      echo "</ul>";
    }

  ?>
