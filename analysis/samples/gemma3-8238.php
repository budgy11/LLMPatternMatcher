
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
  } else { ?>
