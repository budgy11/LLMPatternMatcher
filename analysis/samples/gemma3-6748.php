
    <?php
    // Example product data (replace with your actual product data)
    $products = [
      1 => ['name' => 'Laptop', 'price' => 1200],
      2 => ['name' => 'Mouse', 'price' => 25],
      3 => ['name' => 'Keyboard', 'price' => 75]
    ];

    foreach ($products as $product_id => $product_data):
      echo '<form method="post" action="">
              <label for="product_id_'.$product_id.'">Product: <input type="hidden" name="product_id" value="'.$product_id.'">';
      echo '<input type="text" name="product_name" value="' . $product_data['name'] . '" readonly>';
      echo '<input type="number" name="price" value="' . $product_data['price'] . '" readonly>
              <label for="quantity">Quantity:</label>
              <input type="number" name="quantity" value="1" min="1" max="10" id="quantity">
              <input type="submit" name="action" value="add_to_cart">
            </form><br>';
    endforeach; ?>
