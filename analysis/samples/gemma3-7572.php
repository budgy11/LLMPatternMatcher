    <table border="1">
      <thead>
        <tr>
          <th>Product</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Total</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($cartItems as $key => $item) {
          $product = getProductById($item['product_id']);
          if ($product) {
            echo "<tr>";
            echo "<td>" . $product['name'] . "</td>";
            echo "<td>" . $item['quantity'] . "</td>";
            echo "<td>$" . $product['price'] . "</td>";
            echo "<td>$" . $product['price'] * $item['quantity'] . "</td>";
            echo "<td><a href='?action=remove&product_id=" . $item['product_id'] . "'>Remove</a></td>";
            echo "</tr>";
          }
        }
        ?>
