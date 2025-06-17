

<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Functions to Manage the Cart ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id   The ID of the product being added.
 * @param string $product_name The name of the product (for display purposes).
 * @param int    $quantity     The quantity of the product being added.
 * @param float  $price       The price of a single unit of the product.
 *
 * @return void
 */
function addToCart($product_id, $product_name, $quantity, $price) {
  //  Simple check to ensure quantity is valid (positive integer)
  if (!is_numeric($quantity) || $quantity <= 0) {
    echo "<p style='color:red;'>Invalid quantity.  Please enter a positive number.</p>";
    return;
  }

  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'quantity' => $quantity,
      'price' => $price
    );
  } else {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  }
}


/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id   The ID of the product being updated.
 * @param int    $new_quantity The new quantity for the item.
 *
 * @return void
 */
function updateCartQuantity($product_id, $new_quantity) {
    if (!is_numeric($new_quantity) || $new_quantity <= 0) {
        echo "<p style='color:red;'>Invalid quantity. Please enter a positive number.</p>";
        return;
    }

  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  } else {
    echo "<p style='color:red;'>Product not found in cart.</p>";
  }
}

/**
 * Removes an item from the cart.
 *
 * @param string $product_id   The ID of the product being removed.
 *
 * @return void
 */
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  } else {
    echo "<p style='color:red;'>Product not found in cart.</p>";
  }
}

/**
 * Gets the contents of the cart.
 *
 * @return array  The cart contents.
 */
function getCartContents() {
  return $_SESSION['cart'];
}


/**
 * Calculates the total cost of the cart.
 *
 * @return float The total cost.
 */
function calculateCartTotal() {
  $total = 0;
  $cart = getCartContents();
  foreach ($cart as $item) {
    $total_item = $item['quantity'] * $item['price'];
    $total += $total_item;
  }
  return $total;
}


// --- Example Usage (Simulated) ---

// Add a product to the cart
addToCart('product1', 'Awesome T-Shirt', 2, 20.00);
addToCart('product2', 'Cool Mug', 1, 10.00);

// Update the quantity of a product
updateCartQuantity('product1', 5);


// Get the contents of the cart
$cart_contents = getCartContents();
echo "<h2>Cart Contents:</h2>";
echo "<pre>";
print_r($cart_contents);
echo "</pre>";

// Calculate the total
$total = calculateCartTotal();
echo "<p><strong>Total Cost: $" . $total . "</strong></p>";

// Remove an item
removeCartItem('product2');


// Display cart contents again
$cart_contents = getCartContents();
echo "<h2>Cart Contents:</h2>";
echo "<pre>";
print_r($cart_contents);
echo "</pre>";

// Calculate the total
$total = calculateCartTotal();
echo "<p><strong>Total Cost: $" . $total . "</strong></p>";

?>
