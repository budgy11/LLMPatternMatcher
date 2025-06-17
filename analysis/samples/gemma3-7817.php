<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    table {
      border-collapse: collapse;
      width: 80%;
      margin: 20px auto;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }
    th {
      background-color: #f2f2f2;
    }
  </style>
</head>
<body>

  <h2>Shopping Cart</h2>

  <?php if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
  } else { ?>
