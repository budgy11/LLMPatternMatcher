
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart Example</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <!-- Add to Cart Form -->
  <form method="post" action="">
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="1" min="1">
    <button type="submit" name="add_to_cart">Add to Cart</button>
  </form>

  <!-- Remove Item Button -->
  <a href="?remove_item=<?php echo $productId; ?>">Remove from Cart</a>
