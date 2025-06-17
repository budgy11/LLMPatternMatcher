
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    .success {
      color: green;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <h1>Shopping Cart</h1>

  <!-- Display Cart Items -->
  <?php if (isset($_SESSION['cart'])) { ?>
