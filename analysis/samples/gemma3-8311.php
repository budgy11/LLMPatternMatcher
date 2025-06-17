          <input type="checkbox" id="product_id_<?php echo $product['id']; ?>" name="product_id" value="<?php echo $product['id']; ?>" <?php if (in_array($product['id'], $cartItems)) echo 'checked'; ?> >
          <?php echo $product['name']; ?> (Price: <?php echo $product['price']; ?>)
