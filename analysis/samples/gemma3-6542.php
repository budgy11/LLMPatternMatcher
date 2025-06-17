

<?php
session_start();

// ... (rest of the code, including addToCart, removeFromCart, updateQuantity) ...

// Display a form to add items to the cart
echo "<h2>Add Items to Cart</h2>";
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";
echo "<ul>";
// Product 1
echo "<li>";
echo "<label for='product1'>Laptop:</label>";
echo "<input type='number' id='product1' name='product1' value='1'>";
echo "<button type='submit' name='add_product1'>Add to Cart</button>";
echo "</li>";

// Product 2
echo "<li>";
echo "<label for='product2'>Mouse:</label>";
echo "<input type='number' id='product2' name='product2' value='1'>";
echo "<button type='submit' name='add_product2'>Add to Cart</button>";
echo "</li>";
echo "</ul>";
echo "</form>";

// Handle form submission (if add_product1 or add_product2 is submitted)
if (isset($_POST['add_product1'])) {
    addToCart(1, 'Laptop', 1200, $_POST['product1']);
}
if (isset($_POST['add_product2'])) {
    addToCart(2, 'Mouse', 25, $_POST['product2']);
}


<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --------------------------------------------------
//  Example Functions - Modify as needed for your specific application
// --------------------------------------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity  The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  } else {
    $_SESSION['cart'][$product_id] += $quantity;
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity  The new quantity of the product.
 * @return void
 */
function updateCartItem($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  } else {
    // If the item isn't in the cart, you might want to add it with a quantity of 1
    addToCart($product_id, $quantity); 
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
 * Gets the items in the cart.
 *
 * @return array An array representing the cart contents.  Returns an empty array if the cart is empty.
 */
function getCart() {
  return $_SESSION['cart'];
}



// --------------------------------------------------
// Example Usage (Illustrative - Replace with your actual logic)
// --------------------------------------------------

//  Simulating a product ID and quantity from a form submission.
//  In a real application, this would come from a form or other request.

// Add a product to the cart
// addToCart(123, 2); // Add 2 units of product ID 123

// Update the quantity of an item
// updateCartItem(123, 3); // Change quantity of product 123 to 3

// Remove an item
// removeFromCart(123);


//  Get the current cart contents
$cart_items = getCart();
echo "<h2>Cart Items:</h2>";
if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
  }
  echo "</ul>";
}

?>
