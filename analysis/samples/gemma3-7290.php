    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <input type="hidden" name="user_id" value="123"  /> <!-- Replace 123 with the actual user ID -->
    <input type="submit" value="Add to Cart">
  </form>

</body>
</html>


<?php

// --- Data Storage (Replace with a database for real-world scenarios) ---
$products = [
  1 => ['name' => 'T-Shirt', 'price' => 20, 'quantity' => 50],
  2 => ['name' => 'Jeans', 'price' => 50, 'quantity' => 30],
  3 => ['name' => 'Hat', 'price' => 15, 'quantity' => 100]
];

$cart = []; // Initialize an empty cart

// --- Functions ---

/**
 * Displays the product catalog.
 */
function displayProductCatalog() {
  echo "<h2>Available Products</h2>";
  echo "<table border='1'>";
  echo "<tr><th>Name</th><th>Price</th><th>Quantity</th></tr>";

  foreach ($products as $id => $product) {
    echo "<tr>";
    echo "<td>" . $product['name'] . "</td>";
    echo "<td>$" . $product['price'] . "</td>";
    echo "<td>" . $product['quantity'] . "</td>";
    echo "</tr>";
  }
  echo "</table>";
}

/**
 * Adds a product to the cart.
 *
 * @param int $productId The ID of the product to add.
 * @param int $quantity The quantity to add.
 */
function addToCart(int $productId, int $quantity) {
  if (array_key_exists($productId, $products)) {
    if ($quantity > 0) {
      $product = $products[$productId];

      // Check if the product is already in the cart
      foreach ($cart as &$item) {
        if ($item['productId'] == $productId) {
          $item['quantity'] += $quantity;
          echo "<p>Added " . $quantity . " " . $product['name'] . " to cart.</p>";
          break;
        }
      }

      // If the product is not in the cart, add it
      if (!in_array($productId, array_column($cart, 'productId'))) {
        $cart[] = ['productId' => $productId, 'quantity' => $quantity, 'productName' => $product['name']]; //Store product name for display
        echo "<p>Added " . $quantity . " " . $product['name'] . " to cart.</p>";
      }
    } else {
      echo "<p>Invalid quantity. Please enter a positive number.</p>";
    }
  } else {
    echo "<p>Product not found.</p>";
  }
}


/**
 * Displays the contents of the shopping cart.
 */
function displayCart() {
  echo "<h2>Shopping Cart</h2>";
  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
  } else {
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

    $total = 0;

    foreach ($cart as $item) {
      $product = $products[$item['productId']];
      $itemTotal = $product['price'] * $item['quantity'];
      $total += $itemTotal;
      echo "<tr>";
      echo "<td>" . $product['name'] . "</td>";
      echo "<td>$" . $product['price'] . "</td>";
      echo "<td>" . $item['quantity'] . "</td>";
      echo "<td>$" . $itemTotal . "</td>";
      echo "<td><button onclick='removeFromCart(" . $item['productId'] . ")'>Remove</button></td>"; //Use onclick to call the javascript function
      echo "</tr>";
    }
    echo "</table>";
    echo "<p><strong>Total: $" . $total . "</strong></p>";
  }
}



// --- Handle User Input (Simulated) ---

if (isset($_GET['action']) && $_GET['action'] == 'add_to_cart') {
  $productId = (int)$_GET['productId'];
  $quantity = (int)$_GET['quantity'];
  addToCart($productId, $quantity);
}

// --- Display the Page ---
?>
