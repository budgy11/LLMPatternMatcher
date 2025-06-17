<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    table {
      width: 80%;
      border-collapse: collapse;
      margin: 20px auto;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: center;
    }
  </style>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php
    $cart_items = getCartContents();
    $cart_total = calculateCartTotal();

    if (empty($cart_items)) {
      echo "<p>Your cart is empty.</p>";
    } else {
      echo "<table>";
      echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";
      foreach ($cart_items as $item) {
        $itemTotal = $item['quantity'] * $item['price'];
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td>$" . number_format($item['price'], 2) . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        echo "<td>$" . number_format($itemTotal, 2) . "</td>";
        echo "</tr>";
      }
      echo "</table><p><strong>Total: $" . number_format($cart_total, 2) . "</strong></p>";
    }
  ?>
