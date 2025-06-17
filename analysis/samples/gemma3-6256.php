
  <!-- Update Quantity Form -->
  <form method="post" action="">
    <label for="quantity">Update Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="<?php echo $_SESSION['cart'][$productId] ?? 1; ?>" min="1">
