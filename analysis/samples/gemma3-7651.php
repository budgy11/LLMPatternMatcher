                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
