
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <!-- Add to Cart Form -->
  <h2>Add to Cart</h2>
  <form method="post">
    <label for="item_id">Item ID:</label>
    <input type="number" id="item_id" name="item_id" required><br><br>

    <label for="item_name">Item Name:</label>
    <input type="text" id="item_name" name="item_name" required><br><br>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" step="0.01" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" required><br><br>

    <button type="submit" name="add_to_cart">Add to Cart</button>
  </form>

  <hr>

  <!-- Display Cart Contents -->
  <h2>Shopping Cart</h2>
  <?php display_cart(); ?>
