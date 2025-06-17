            <td><?php echo $item['price'] * $item['quantity']; ?></td>
            <td>
              <form method="post">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
