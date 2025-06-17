

<?php
session_start();

// Assuming you have a database connection established (e.g., $db)

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity  The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity) {
  if (isset($_SESSION['cart'])) {
    $_SESSION['cart'][$product_id] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  } else {
    // Handle the case where the product is not in the cart
    // You might want to log this or display an error message
    // For this example, we'll just do nothing.
  }
}

/**
 * Removes an item from the cart.
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
 * @return array The cart contents as an associative array.
 */
function getCartContents() {
  return $_SESSION['cart'] ?? []; // Return empty array if cart isn't initialized. Use null coalescing operator.
}


/**
 * Calculates the total cost of the cart.
 *
 * @return float The total cost.
 */
function calculateTotal() {
  $total = 0;
  $cart = getCartContents();
  foreach ($cart as $product_id => $quantity) {
    // Assume you have a function to get the product price (e.g., getProductPrice($product_id))
    $price = getProductPrice($product_id); // Replace with your actual function call
    $totalForProduct = $price * $quantity;
    $total += $totalForProduct;
  }
  return $total;
}


/**
 *  Placeholder function to get product price.  Replace with your database query or logic.
 * @param int $product_id
 * @return float
 */
function getProductPrice($product_id) {
    //  Replace this with your database query or other logic to get the price
    //  This is just a placeholder
    switch($product_id) {
        case 1: return 10.00;
        case 2: return 25.50;
        default: return 0;
    }
}



// ---  Shopping Cart Implementation (Example) ---

// 1. Add to Cart (Example)
if (isset($_POST['add_to_cart'])) {
  $product_id = (int)$_POST['product_id'];
  $quantity = (int)$_POST['quantity'];

  addToCart($product_id, $quantity);
  echo "<p>Item added to cart.  Total: " . calculateTotal() . "</p>";
}

// 2. Update Cart Quantity
if (isset($_POST['update_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    updateCartQuantity($product_id, $quantity);
    echo "<p>Cart updated.  Total: " . calculateTotal() . "</p>";
}


// 3. Remove from Cart
if (isset($_POST['remove_from_cart'])) {
  $product_id = (int)$_POST['product_id'];
  removeFromCart($product_id);
  echo "<p>Item removed from cart.  Total: " . calculateTotal() . "</p>";
}

// 4. Display Cart Contents
$cartContents = getCartContents();
echo "<h2>Your Shopping Cart</h2>";

if (empty($cartContents)) {
    echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartContents as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
  }
  echo "</ul>";
  echo "<p>Total Cost: $" . calculateTotal() . "</p>";
}
?>
