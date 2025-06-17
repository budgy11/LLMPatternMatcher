

<?php

session_start();

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  // Check if the cart already exists
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'price'    => // You'd typically get this from a database
      //  e.g., $product_price
      //  For simplicity, let's assume $price = 20
      'price' => 20
    ];
  }
}


/**
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity of the product.
 * @return void
 */
function updateCartQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
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
 * Calculates the total cart value.
 *
 * @return float The total value of the cart.
 */
function calculateCartTotal() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['quantity'] * $item['price']; // Assuming 'price' is in each item
    }
  }
  return $total;
}


/**
 * Gets the contents of the cart.
 *
 * @return array The contents of the cart.
 */
function getCartContents() {
    return $_SESSION['cart'] ?? [];
}

// --- Example Usage (Demonstration) ---

// 1. Add a product to the cart
addToCart(123); // Add 1 of product ID 123
addToCart(456, 2); // Add 2 of product ID 456
addToCart(123, 3); // Add 3 of product ID 123


// 2. Update the quantity of a product
updateCartQuantity(123, 5); // Change the quantity of product 123 to 5

// 3. Remove a product from the cart
removeFromCart(456);

// 4. Calculate the total
$total = calculateCartTotal();
echo "Total cart value: $" . number_format($total, 2) . "<br>";

// 5. Get the cart contents
$cartContents = getCartContents();
echo "Cart Contents:<br>";
echo "<pre>";
print_r($cartContents);
echo "</pre>";


?>
