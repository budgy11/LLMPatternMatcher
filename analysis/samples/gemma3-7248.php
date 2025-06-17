          <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
          <button type="submit" name="update_cart">Update</button>
        </form>
        <form action="cart.php" method="post">
          <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
