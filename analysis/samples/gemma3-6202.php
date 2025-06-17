
<!-- HTML form to add items to the cart -->
<form method="post" action="">
  <h2>Add to Cart</h2>
  <?php
  foreach ($products as $id => $product) {
    echo "<label for='" . $id . "'>" . $product['name'] . " ($" . number_format($product['price'], 2) . ")</label><br>";
    echo "<input type='number' id='" . $id . "' name='product_id' value='" . $id . "' min='1' max='10'><br>"; // Added min and max for input validation
  }
  ?>
