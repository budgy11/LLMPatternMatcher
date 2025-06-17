      </tbody>
    </table>

    <p>Total: $<?php
      $total = 0;
      foreach ($cart_items as $product_id => $product) {
        $total += $product['price'] * $product['quantity'];
      }
      echo number_format($total, 2);
    ?>
