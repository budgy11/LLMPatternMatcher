                    <td><?php echo $item_total; ?></td>
                    <td>
                        <form action="cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
