
  <!-- Add to Cart Buttons (for demonstration -  you'd typically use a form) -->
  <?php
  echo "<h2>Add to Cart</h2>";
  foreach ($items as $item) {
    echo "<p><a href='?add=" . $item['id'] . "&name=" . urlencode($item['name']) . "&price=" . $item['price'] . "'>Add " . $item['name'] . " to Cart</a></p>";
  }
  ?>
