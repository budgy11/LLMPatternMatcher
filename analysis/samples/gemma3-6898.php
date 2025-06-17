
<!DOCTYPE html>
<html>
<head>
  <title>Simple Purchase Cart</title>
  <style>
    .cart-item {
      border: 1px solid #ccc;
      padding: 10px;
      margin-bottom: 10px;
    }
    .cart-total {
      font-weight: bold;
      margin-top: 20px;
    }
  </style>
</head>
<body>

  <h1>Purchase Cart</h1>

  <form method="post">
    <?php if (count($cart) > 0): ?>
