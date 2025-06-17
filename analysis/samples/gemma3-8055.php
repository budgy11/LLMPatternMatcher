            <td><?php echo $item['price'] * $item['quantity']; ?></td>
            <td>
              <a href="cart_update.php?product_id=<?php echo $item['product_id']; ?>&quantity=<?php echo $item['quantity']; ?>&action=update">Update</a> |
