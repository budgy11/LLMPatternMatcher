                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" style="width: 60px;">
                            <button type="submit" name="update_quantity">Update</button>
                        </form>
                        <form method="post" action="cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
