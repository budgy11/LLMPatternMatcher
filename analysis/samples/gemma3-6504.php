</form>

<!-- Form to update quantity -->
<form method="post" action="">
  <h2>Update Quantity</h2>
  <?php
    foreach ($products as $product_id => $product) {
        echo "<label for='" . $product_id . "'>" . $product['name'] . "</label><br>";
        echo "<input type='number' id='" . $product_id . "' name='product_id' value='" . $product_id . "' min='1' size='3'><br>";
        echo "<input type='submit' name='update_quantity' value='Update ' . $product['name']  />";
    }
  ?>
