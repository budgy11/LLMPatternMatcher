            <td><?php echo number_format($item_data['price'] * $item_data['quantity'], 2); ?></td>
            <td>
              <a href="?action=remove_from_cart&product_id=<?php echo $item_id; ?>">Remove</a>
