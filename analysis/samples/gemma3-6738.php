
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    .cart-item {
      margin-bottom: 10px;
      border: 1px solid #ddd;
      padding: 10px;
    }
  </style>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php if (empty($cart_contents)): ?>
