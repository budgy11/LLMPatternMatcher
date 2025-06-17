            <td>$<?php echo number_format($item["price"] * $item["quantity"], 2); ?></td>
            <td>
              <a href="?action=remove&product_id=<?php echo $item["id"]; ?>">Remove</a>
