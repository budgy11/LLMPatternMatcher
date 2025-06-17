
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

  <form method="post" action="">
    <button type="submit" name="clear_cart">Clear Cart</button>
  </form>

  <?php if (empty($cart_contents)): ?>
