        </select>
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" value="1" min="1">
        <input type="submit" name="add_to_cart" value="Add to Cart">
    </form>

</body>
</html>


<?php

session_start();

// -------------------- Cart Functions --------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = $quantity;
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
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity for the product.
 * @return void
 */
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

/**
 * Gets all items in the cart.
 *
 * @return array An array containing all items in the cart.
 */
function getCart() {
  return $_SESSION['cart'] ?? [];
}

/**
 * Clears the entire cart.
 *
 * @return void
 */
function clearCart() {
  unset($_SESSION['cart']);
}

// -------------------- Example Usage (Simulated Product Data) --------------------

//  Simulate a product catalog (replace with your database connection)
$products = [
  1 => ['name' => 'T-Shirt', 'price' => 20],
  2 => ['name' => 'Jeans', 'price' => 50],
  3 => ['name' => 'Hat', 'price' => 15],
];


// -------------------- Example Usage (Shopping Cart Interactions) --------------------

// Add a T-Shirt to the cart
addToCart(1);

// Add 2 pairs of Jeans to the cart
addToCart(2, 2);

// Update the quantity of the T-Shirt to 3
updateCartQuantity(1, 3);

// Get the current cart contents
$cart = getCart();
echo "<h2>Cart Contents:</h2>";
echo "<pre>";
print_r($cart);
echo "</pre>";


// Remove the Hat from the cart
removeFromCart(3);

// Get the updated cart contents
$cart = getCart();
echo "<h2>Cart Contents After Removal:</h2>";
echo "<pre>";
print_r($cart);
echo "</pre>";

// Clear the cart
clearCart();

// Get the empty cart
$cart = getCart();
echo "<h2>Cart Contents After Clearing:</h2>";
echo "<pre>";
print_r($cart);
echo "</pre>";

?>
