                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                                <button type="submit">Update</button>
                            </form>
                            <form method="post">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
