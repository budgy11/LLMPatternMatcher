    <input type="text" name="search_term" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php

  // 2.  Handle the Search Query
  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $search_term = htmlspecialchars($_POST["search_term"]); // Sanitize the input

    // Your logic here to search through your data (e.g., database, array)
    //  This is a placeholder.  Replace with your actual search logic.

    if ($search_term != "") {
      echo "<p>You searched for: " . $search_term . "</p>";
      // Example: Search through an array (replace with your actual data source)
      $data = ["apple", "banana", "orange", "grapefruit", "kiwi"];
      $results = array_filter($data, function($item) use ($search_term) {
        return stripos($item, $search_term) !== false; // Case-insensitive search
      });

      if (count($results) > 0) {
        echo "<ul>";
        foreach ($results as $result) {
          echo "<li>" . $result . "</li>";
        }
        echo "</ul>";
      } else {
        echo "<p>No results found.</p>";
      }
    }
  }

?>
