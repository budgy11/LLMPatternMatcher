
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <form method="post" action="">
    <label for="item_id">Item ID:</label>
    <input type="number" id="item_id" name="item_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <input type="submit" value="Add to Cart">
  </form>

  <?php
    // You'll likely want to display the contents of the cart here,
    // using queries to retrieve items from the cart_items table.
  ?>
