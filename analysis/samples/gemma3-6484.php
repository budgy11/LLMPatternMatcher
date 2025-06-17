        </select>
        <button type="submit" name="add_to_cart">Add to Cart</button>
    </form>

    <a href="checkout.php">Checkout</a>  <!--  Example of how to link to a checkout page -->

</body>
</html>


<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// ------------------  Helper Functions ------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function add_to_cart($product_id, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it is, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it's not, add the product with its quantity
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
  }
}


/**
 * Removes a product from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity of the product.
 * @return void
 */
function update_cart_quantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}



// ------------------ Example Usage  ------------------

// 1. Add a product to the cart
add_to_cart(123); // Add product with ID 123, quantity 1
add_to_cart(456, 2); // Add product with ID 456, quantity 2

// 2. Update the quantity of a product
update_cart_quantity(123, 5); // Change quantity of product 123 to 5

// 3. Remove a product from the cart
remove_from_cart(456);


// 4. Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}



// ------------------ Notes ------------------

// - This is a basic example and can be expanded to include:
//   - Product names, prices, images, etc.
//   - Session expiry settings
//   - Better user interface and display
// -  Security:  In a production environment, you would need to:
//    - Validate product IDs to prevent malicious input.
//    - Implement session security measures (e.g., session ID regeneration).
//    - Consider using a database to store cart information for scalability.
?>
