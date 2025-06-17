                    <td>$<?php echo number_format($item['product_price'] * $item['quantity'], 2); ?></td>
                    <td>
                        <form method="get" action="cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
