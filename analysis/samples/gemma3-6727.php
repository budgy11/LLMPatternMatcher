

<?php
session_start();

// ---------------------  Cart Functions ---------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity  The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  if (isset($_SESSION['cart'])) {
    $_SESSION['cart'][] = $product_id;
  } else {
    $_SESSION['cart'] = [$product_id];
  }
}

/**
 * Retrieves the contents of the cart.
 *
 * @return array The array containing the product IDs in the cart.
 */
function getCart() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  } else {
    return []; // Return an empty array if the cart is empty
  }
}

/**
 * Removes an item from the cart by product ID.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart'][array_search($product_id, $_SESSION['cart'])]);
  }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity for the product.
 * @return void
 */
function updateCartQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'])) {
    // Find the index of the product in the cart
    $index = array_search($product_id, $_SESSION['cart']);

    // Check if the product exists in the cart
    if ($index !== false) {
      $_SESSION['cart'][$index] = $new_quantity;
    }
  }
}


// ---------------------  Example Usage (Simulated) ---------------------

// For demonstration purposes, let's simulate adding items to the cart.
// In a real application, you would typically do this based on user actions
// (e.g., clicking an "Add to Cart" button).

// Example 1: Add product 1 to cart with quantity 2
addToCart(1, 2);

// Example 2: Add product 2 to cart with default quantity (1)
addToCart(2);

// Example 3: Update quantity of product 1 to 3
updateCartQuantity(1, 3);

// ---------------------  Cart Contents (for Debugging) ---------------------

// Display the cart contents
$cart_contents = getCart();
echo "<h2>Cart Contents:</h2>";
if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $product_id) {
    echo "<li>Product ID: " . $product_id . "</li>";
  }
  echo "</ul>";
}

//  Example of removing a product
// removeFromCart(1);
// echo "<h2>Cart Contents after removing product 1:</h2>";
// $cart_contents = getCart();
// if (empty($cart_contents)) {
//   echo "<p>Your cart is empty.</p>";
// } else {
//   echo "<ul>";
//   foreach ($cart_contents as $product_id) {
//     echo "<li>Product ID: " . $product_id . "</li>";
//   }
//   echo "</ul>";
// }



?>
