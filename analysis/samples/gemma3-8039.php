    <p>Cart Items:</p>
    <ul>
      <?php
      foreach ($_SESSION['cart'] as $product_id => $item) {
        // Find the product details from the $product_data array
        $product = null;
        foreach ($product_data as $p) {
          if ($p['id'] == $product_id) {
            $product = $p;
            break;
          }
        }

        if ($product) {
          echo "<li>" . $product['name'] . " - $" . $product['price'] . " - Quantity: <input type='number' value='" . $item['quantity'] . "' min='1'></li>";
        }
      }
      ?>
