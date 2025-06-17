

<?php
session_start();

// ------------------------------------------------------------------
// Cart Management Functions
// ------------------------------------------------------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity to add. Defaults to 1.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  if (isset($_SESSION['cart'])) {
    $_SESSION['cart'][$product_id] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

/**
 * Removes a product from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Gets the cart contents.
 *
 * @return array The cart contents.
 */
function getCart() {
  return $_SESSION['cart'];
}

/**
 * Calculates the total number of items in the cart.
 *
 * @return int The total number of items.
 */
function getCartTotal() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity) {
      $total += $quantity;
    }
  }
  return $total;
}

/**
 * Clears the entire cart.
 *
 * @return void
 */
function clearCart() {
  unset($_SESSION['cart']);
}


// ------------------------------------------------------------------
// Example Usage (Simulated Product Data - Replace with your database)
// ------------------------------------------------------------------

$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75],
];


// ------------------------------------------------------------------
// Handle Add to Cart Request (Simulated -  In a real app, this would come from a form submission)
// ------------------------------------------------------------------

if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;

  // Validate the quantity (optional, but good practice)
  if (is_numeric($quantity) && $quantity > 0) {
    addToCart($product_id, $quantity);
    echo "<p>Item added to cart.</p>";
  } else {
    echo "<p style='color:red;'>Invalid quantity. Please enter a positive number.</p>";
  }
}


// ------------------------------------------------------------------
// Display the Cart (Example)
// ------------------------------------------------------------------

echo "<h1>Shopping Cart</h1>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $product_name = $products[$product_id]['name'];
    $product_price = $products[$product_id]['price'];
    echo "<li>$product_name - Quantity: $quantity - Price: $product_price</li>";
  }
  echo "</ul>";

  echo "<p>Total Items in Cart: " . getCartTotal() . "</p>";
  echo "<a href='clear_cart.php' >Clear Cart</a>";  // Link to clear the cart
}

?>
