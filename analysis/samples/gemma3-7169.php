                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td>
                            <a href="?remove_from_cart=<?php echo $item['id']; ?>">Remove</a>
