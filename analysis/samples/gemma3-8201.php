                            <input type="hidden" name="quantity" value="<?php echo $item['quantity']; ?>">
                            <button type="submit">Remove</button>
                        </form>
                        <form action="cart.php" method="PUT">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
