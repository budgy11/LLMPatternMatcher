
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <!-- Cart Display (as shown above) -->

  <h2>Add to Cart</h2>
  <ul>
    <li><a href="cart.php?add=1">Product 1 (Quantity: 2)</a></li>
    <li><a href="cart.php?add=2">Product 2 (Quantity: 1)</a></li>
    <li><a href="cart.php?add=1">Product 1 (Quantity: 3)</a></li>
  </ul>

  <h2>Remove from Cart</h2>
  <ul>
    <li><a href="cart.php?remove=2">Remove Product 2</a></li>
  </ul>

  <!-- Form to update quantity -->
  <h2>Update Quantity</h2>
  <form method="post" action="cart.php">
    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" value="2">
    <br>
    <label for="quantity">New Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="5">
    <br>
    <button type="submit" name="update_quantity">Update</button>
  </form>

</body>
</html>


<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// --- Functions for Cart Management ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function add_to_cart($product_id, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    // If the product is not already in the cart, add it with a quantity of 1
    $_SESSION['cart'][$product_id] = $quantity;
  } else {
    // If the product is already in the cart, increase the quantity
    $_SESSION['cart'][$product_id] += $quantity;
  }
}

/**
 * Removes an item from the cart.
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
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity of the product.
 * @return void
 */
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}



// --- Example Usage (Simulating User Actions) ---

// 1.  Adding an item to the cart:
add_to_cart(123); // Add product with ID 123 to the cart, quantity = 1
add_to_cart(456, 2); // Add product with ID 456 to the cart, quantity = 2

// 2.  Updating the quantity of an item:
update_cart_quantity(123, 3);  // Change quantity of product 123 to 3


// 3. Removing an item:
remove_from_cart(456);


// --- Displaying the Cart Contents (for demonstration) ---

echo "<h2>Your Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    // Simulate retrieving product details (replace with actual database query)
    $product_name = "Product " . $product_id; // Placeholder
    echo "<li>$product_name x $quantity  -  Total: $quantity</li>";
  }
  echo "</ul>";
}
?>
