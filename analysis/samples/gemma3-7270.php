
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    .cart-item {
      border: 1px solid #ccc;
      padding: 10px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php if (empty($cart_items)) {
      echo "<p>Your cart is empty.</p>";
    } else {
        echo "<table class='cart-items'>";
        echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

        foreach ($cart_items as $item) {
          echo '<tr class="cart-item">';
          echo "<td>" . $item['name'] . "</td>";
          echo "<td>$" . $item['price'] . "</td>";
          echo "<td>" . $item['quantity'] . "</td>";
          echo "<td>$" . $item['quantity'] * $item['price'] . "</td>";
          echo "<td><form method='post'><input type='hidden' name='product_id' value='" . $item['id'] . "'> <button type='submit'>Remove</button></form></td>";
          echo "</tr>";
        }

        echo "</table>";
        echo "<p><strong>Total: $" . $cart_total . "</strong></p>";
    }

    ?>
