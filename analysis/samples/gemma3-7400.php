
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    .cart-item {
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <h2>Shopping Cart</h2>

  <?php if (empty($items)) {
    echo "<p>Your cart is empty.</p>";
  } else { ?>
