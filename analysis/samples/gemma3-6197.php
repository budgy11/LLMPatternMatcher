

<?php

// Start a session (if it doesn't exist)
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// ------------------------------------------------------------------
// Example Functions for Adding to Cart
// ------------------------------------------------------------------

/**
 * Adds an item to the shopping cart.
 *
 * @param string $product_id The ID of the product being added.
 * @param string $product_name The name of the product.
 * @param int $quantity The quantity of the product to add.  Defaults to 1.
 * @param float $price The price of the product.
 */
function add_to_cart(string $product_id, string $product_name, int $quantity = 1, float $price = 0.0) {
  //Check if the item already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Item already exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Item doesn't exist, add a new entry
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'quantity' => $quantity,
      'price' => $price
    );
  }
}



// ------------------------------------------------------------------
// Example Usage (Illustrative)
// ------------------------------------------------------------------

// Add a product to the cart
add_to_cart("product1", "Awesome T-Shirt", 2, 25.00);
add_to_cart("product2", "Cool Hat", 1, 15.00);


// Display the contents of the cart
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>" . $product_details['name'] . " - Quantity: " . $product_details['quantity'] . " - Price: $" . $product_details['price'] . "</li>";
  }
  echo "</ul>";

  // Calculate the total cost
  $total = 0;
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    $total += $product_details['quantity'] * $product_details['price'];
  }
  echo "<p><strong>Total: $" . number_format($total, 2) . "</strong></p>";
}


// ------------------------------------------------------------------
// Example Functions for Removing from Cart
// ------------------------------------------------------------------

/**
 * Removes an item from the shopping cart.
 *
 * @param string $product_id The ID of the product to remove.
 */
function remove_from_cart(string $product_id)
{
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Example: Remove 'product1' from the cart
//remove_from_cart("product1");


// ------------------------------------------------------------------
// Example Functions for Updating Cart Quantity
// ------------------------------------------------------------------

/**
 * Updates the quantity of an item in the shopping cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity of the product.
 */
function update_cart_quantity(string $product_id, int $new_quantity)
{
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
  // You might want to add error handling here to ensure that the quantity
  // is a valid number (e.g., positive).
}


// Example: Update the quantity of 'product2' to 3
//update_cart_quantity("product2", 3);
?>
