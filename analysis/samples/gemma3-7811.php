                    <td><?php echo $item['quantity'] * $item['price']; ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
