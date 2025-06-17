
  <hr>

  <h2>Add to Cart</h2>
  <?php
    // Example Products (Replace with your actual product data)
    $products = [
        'product1' => ['id' => 'product1', 'name' => 'Laptop', 'price' => 1200],
        'product2' => ['id' => 'product2', 'name' => 'Mouse', 'price' => 25],
        'product3' => ['id' => 'product3', 'name' => 'Keyboard', 'price' => 75]
    ];

    foreach ($products as $id => $product) {
        echo '<form method="post" action="cart.php">';
        echo '<label for="' . $id . '">Product ID:</label> <input type="hidden" id="' . $id . '" name="product_id" value="' . $id . '">';
        echo '<label for="' . $id . '">Product Name:</label> <input type="text" id="' . $id . '" name="product_name" value="' . $product['name'] . '" readonly>';
        echo '<label for="' . $id . '">Price:</label> <input type="text" id="' . $id . '" name="price" value="' . $product['price'] . '" readonly>';
        echo '<label for="' . $id . '">Quantity:</label> <input type="number" id="' . $id . '" name="quantity" value="1" min="1" max="100">';
        echo '<input type="submit" name="add_to_cart" value="Add to Cart">';
        echo '</form><br>';
    }
  ?>
