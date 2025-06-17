

<?php

// Start a session if it doesn't exist
session_start();

// -----------------------------------------------------
//  Utility Functions (Helper functions)
// -----------------------------------------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart(int $product_id, int $quantity = 1) {
  // Check if the cart exists in session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment quantity if product already exists
    $_SESSION['cart'][$product_id] += $quantity;
  } else {
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

/**
 * Removes a product from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart(int $product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Gets the items in the cart.
 *
 * @return array The items in the cart.
 */
function getCart() {
  return $_SESSION['cart'] ?? [];
}

/**
 * Gets the total number of items in the cart.
 *
 * @return int
 */
function cartTotal() {
    $cart = getCart();
    return count($cart);
}

/**
 *  Gets the total price of items in the cart (assuming you have a price for each product)
 * @return float|null
 */
function calculateCartTotal(array $cart) {
    $total = 0;
    foreach ($cart as $product_id => $quantity) {
        //  You'll need to replace this with your product price retrieval logic.
        //  This example assumes you have a database or data source to get the price.
        $price = getProductPrice($product_id);
        if ($price !== null) { //Check if price exists
            $totalPrice = $price * $quantity;
            $totalPrice += $totalPrice;
        } else {
            // Handle the case where the product price is not available.
            //  You might log an error, return null, or assign a default value.
            //  For this example, we'll continue with the next product.
        }
    }
    return $totalPrice;
}

/**
 * Mock function to retrieve product price. Replace with your real implementation.
 * @param int $product_id
 * @return int|null
 */
function getProductPrice(int $product_id) {
    //  Replace this with your actual logic to fetch the price from a database or other source.
    //  This is just a placeholder.
    if ($product_id == 1) {
        return 10.00;
    } elseif ($product_id == 2) {
        return 25.50;
    } else {
        return null; // Product not found, or no price available
    }
}

// -----------------------------------------------------
//  Example Usage (Inside your web page)
// -----------------------------------------------------

// Add some items to the cart
addToCart(1, 2); // Add 2 of product 1
addToCart(2);     // Add 1 of product 2
addToCart(1, 3); // Add 3 of product 1

// Display the contents of the cart
echo "<h2>Cart Items:</h2>";
$cartItems = getCart();
if (count($cartItems) > 0) {
    echo "<ul>";
    foreach ($cartItems as $product_id => $quantity) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Your cart is empty.</p>";
}


// Display the total number of items
echo "<p>Total Items in Cart: " . cartTotal() . "</p>";

// Display the total price
$totalPrice = calculateCartTotal($cartItems);
echo "<p>Total Price: $" . $totalPrice . "</p>"; // Use calculateCartTotal
?>
