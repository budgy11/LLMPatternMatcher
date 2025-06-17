
    <a href="checkout.php">Checkout</a>  <!-- Link to checkout page (not implemented) -->

</body>
</html>


<?php
session_start();

// Assuming you have a database connection established (e.g., $db)

// Function to add to cart
function addToCart($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
  }
}

// Function to get cart items
function getCartItems() {
  return isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
}

// Function to update cart quantity
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  } else {
    // Handle the case where the product is not in the cart
    // You might want to add it with the given quantity or handle it differently
    // For this example, we'll just return an empty array to indicate no update
    return array();
  }
  return $_SESSION['cart'];
}

// Function to remove item from cart
function removeItemFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
  return $_SESSION['cart'];
}

// Function to calculate cart total
function calculateCartTotal() {
  $total = 0;
  $cartItems = getCartItems();
  foreach ($cartItems as $product_id => $item) {
    $product = getProductById($product_id); // Assuming you have a function to get product details
    if ($product) {
      $totalPrice = $product['price'] * $item['quantity'];
      $total += $totalPrice;
    }
  }
  return $total;
}

//Example Product Retrieval Function - Replace with your actual database query
function getProductById($product_id) {
    // Replace this with your database query to retrieve product details
    // based on the product_id
    $products = array(
        1 => array('id' => 1, 'name' => 'Laptop', 'price' => 1200),
        2 => array('id' => 2, 'name' => 'Mouse', 'price' => 25),
        3 => array('id' => 3, 'name' => 'Keyboard', 'price' => 75)
    );
    if (isset($products[$product_id])) {
        return $products[$product_id];
    }
    return null;
}

// Handling Add to Cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  addToCart($product_id, $quantity);
  // Optionally, display a success message
  echo "<p>Item added to cart!</p>";
}

// Handling Update Quantity
if (isset($_POST['update_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  updateCartQuantity($product_id, $quantity);
  // Optionally, display a success message
  echo "<p>Cart updated!</p>";
}

// Handling Remove Item
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  removeItemFromCart($product_id);
  // Optionally, display a success message
  echo "<p>Item removed from cart!</p>";
}

// Display Cart Items
$cartItems = getCartItems();
$cartTotal = calculateCartTotal();

echo "<h2>Your Shopping Cart</h2>";

if (empty($cartItems)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartItems as $product_id => $item) {
    $product = getProductById($product_id);
    if ($product) {
      echo "<li>" . $product['name'] . " - $" . $product['price'] . " x " . $item['quantity'] . " = $" . ($product['price'] * $item['quantity']) . "</li>";
    }
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . $cartTotal . "</strong></p>";
}
?>
