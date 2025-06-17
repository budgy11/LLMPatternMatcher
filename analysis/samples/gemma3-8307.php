    <table>
      <thead>
        <tr>
          <th>Product</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Total</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($cartItems as $item) {
          echo "<tr class='cart-item'>";
          echo "<td>" . $item['name'] . "</td>";
          echo "<td>$" . $item['price'] . "</td>";
          echo "<td>" . $item['quantity'] . "</td>";
          echo "<td>$" . $item['quantity'] * $item['price'] . "</td>";
          echo "<td><a href='?action=remove_cart&product_id=" . $item['id'] . "'>Remove</a></td>";
          echo "</tr>";
        }
        ?>
