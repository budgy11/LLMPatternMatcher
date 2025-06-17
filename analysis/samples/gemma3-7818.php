    <table>
      <thead>
        <tr>
          <th>Product Name</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Total</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $total = 0;
        foreach ($cart_contents as $item) {
          $product_name = $item['product_name'];
          $price = $item['price'];
          $quantity = $item['quantity'];
          $total_item = $price * $quantity;
          $total += $total_item;

          echo "<tr>";
          echo "<td>" . $product_name . "</td>";
          echo "<td>$" . number_format($price, 2) . "</td>";
          echo "<td>" . $quantity . "</td>";
          echo "<td>$" . number_format($total_item, 2) . "</td>";
          echo "<td><a href='cart.php?action=remove&product_id=" . $item['product_id'] . "'>Remove</a></td>";
          echo "</tr>";
        } ?>
