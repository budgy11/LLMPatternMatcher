    <table id="cart-table">
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
        foreach ($cart_items as $key => $item) {
          $total_item_price = $item['price'] * $item['quantity'];
          echo '<tr class="cart-item">
                <td>' . $item['name'] . '</td>
                <td>$' . number_format($item['price'], 2) . '</td>
                <td>' . $item['quantity'] . '</td>
                <td>$' . number_format($total_item_price, 2) . '</td>
                <td>
                  <form action="cart.php" method="post">
                    <input type="hidden" name="action" value="update_quantity">
                    <input type="hidden" name="product_id" value="' . $item['id'] .'">
                    <input type="number" name="quantity" value="' . $item['quantity'] .'" min="1" style="width:50px;">
                    <button type="submit">Update</button>
                  </form>
                  <form action="cart.php" method="post">
                    <input type="hidden" name="action" value="remove_item">
                    <input type="hidden" name="product_id" value="' . $item['id'] .'">
                    <button type="submit">Remove</button>
                  </form>
                </td>
              </tr>';
        } ?>
