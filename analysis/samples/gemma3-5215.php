    <h2>Search Results:</h2>
    <ul>
      <?php
      foreach ($results as $result) {
        echo "<li>" . htmlspecialchars($result) . "</li>"; // Use htmlspecialchars for output
      }
      ?>
