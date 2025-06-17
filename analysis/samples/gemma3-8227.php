                    <td><?php echo $total_for_product; ?></td>
                    <td>
                        <form method="post" action="cart.php">
                            <input type="hidden" name="action" value="remove_from_cart">
                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
