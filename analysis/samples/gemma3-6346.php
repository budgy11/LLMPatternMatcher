

<?php
session_start(); // Start the session

// -----------------------------------------------------
// Helper Functions
// -----------------------------------------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity  The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity) {
  if (empty($_SESSION['cart'])) {
    // Cart is empty, initialize it
    $_SESSION['cart'] = [];
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increase quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
  }
}

/**
 * Removes a specific item from the cart.
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
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity  The new quantity.
 * @return void
 */
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

/**
 * Gets all items in the cart.
 *
 * @return array  An array representing the cart contents.
 */
function getCartItems() {
    return $_SESSION['cart'] ?? []; // Return empty array if cart is empty
}



// -----------------------------------------------------
// Example Usage (Simulating a Product View)
// -----------------------------------------------------

// Simulate a user adding an item to the cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  addToCart($product_id, $quantity);
}

// Example: Remove an item
if (isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];
    removeFromCart($product_id);
}


// Example: Update the quantity of an item
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    updateQuantity($product_id, $new_quantity);
}

// -----------------------------------------------------
// Displaying the Cart (for demonstration)
// -----------------------------------------------------

// Get the cart items
$cart_items = getCartItems();

// Display the cart contents
echo "<div class='cart'>";
if (empty($cart_items)) {
    echo "<h2>Your cart is empty.</h2>";
} else {
    echo "<h2>Your Shopping Cart</h2>";
    echo "<ul>";
    foreach ($cart_items as $product_id => $item) {
        echo "<li>";
        echo "Product ID: " . $product_id . "<br>";
        echo "Quantity: " . $item['quantity'] . "<br>";
        // You would likely fetch product details from a database based on $product_id here
        echo "</li>";
    }
    echo "</ul>";
}
echo "</div>";

?>
