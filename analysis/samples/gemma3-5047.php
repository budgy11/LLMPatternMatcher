    <ul>
      <?php
      foreach ($searchResults as $result) {
        echo "<li>" . htmlspecialchars($result) . "</li>";
      }
      ?>
