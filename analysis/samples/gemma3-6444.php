
<!-- Example HTML Form to Add to Cart -->
<form method="post" action="">
  <label for="product_id">Product ID:</label>
  <select name="product_id" id="product_id">
    <option value="1">Laptop</option>
    <option value="2">Mouse</option>
    <option value="3">Keyboard</option>
  </select><br><br>

  <label for="quantity">Quantity:</label>
  <input type="number" id="quantity" name="quantity" value="1" min="1">

  <input type="submit" value="Add to Cart">
</form>


<?php

session_start(); // Start the PHP session

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// --- Example Functions to Add/Remove Items ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product to add.
 * @param string $product_name The name of the product.
 * @param int    $quantity   The quantity of the product to add.
 * @param float  $price      The price of the product.
 *
 * @return void
 */
function add_to_cart(string $product_id, string $product_name, int $quantity, float $price) {
    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Product exists, update the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Product doesn't exist, add it to the cart
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'quantity' => $quantity,
            'price' => $price
        );
    }
}


/**
 * Removes a product from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 *
 * @return void
 */
function remove_from_cart(string $product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int    $newQuantity The new quantity.
 *
 * @return void
 */
function update_cart_quantity(string $product_id, int $newQuantity) {
  if(isset($_SESSION['cart'][$product_id])){
    $_SESSION['cart'][$product_id]['quantity'] = $newQuantity;
  }
}

// --- Example Usage (Simulating a Product Purchase) ---

// Add a product to the cart
add_to_cart('product1', 'Awesome T-Shirt', 2, 25.00);

// Add another product
add_to_cart('product2', 'Cool Hat', 1, 15.00);

// Update the quantity of product1 to 5
update_cart_quantity('product1', 5);


// Remove a product from the cart
remove_from_cart('product2');



// --- Displaying the Cart Contents (for demonstration) ---
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_data) {
        echo "<li>";
        echo "<strong>" . $product_data['name'] . "</strong> - Quantity: " . $product_data['quantity'] . " - Price: $" . $product_data['price'] . "</li>";
    }
    echo "</ul>";
}


// ---  End of the Session  (Important!) ---
// In a real application, you'd likely have a logout or session expiration mechanism
// to properly terminate the session.
?>
