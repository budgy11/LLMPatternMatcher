                    <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    <td>
                        <a href="?action=remove&item_id=<?php echo $item['item_id']; ?>">Remove</a>
