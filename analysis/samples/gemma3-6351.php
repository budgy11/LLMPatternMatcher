

<?php
session_start();

// Initialize the cart (if it doesn't exist)
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// --- Functions for Cart Operations ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product being added.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it's already there, update the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it's not there, add it to the cart as a new item
    $_SESSION['cart'][$product_id] = array(
      'quantity' => $quantity,
      'price' => getProductPrice($product_id) // You'll need a function to get product price
    );
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
 * @param int $new_quantity The new quantity of the product.
 * @return void
 */
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}


/**
 * Gets the price of a product based on its ID.  (This needs to be implemented)
 *
 * @param int $product_id
 * @return float|null
 */
function getProductPrice($product_id) {
    // Replace this with your actual database query or other method
    // to retrieve the product price.  This is just a placeholder.
    //  Example:
    //  $db = new DatabaseConnection();
    //  $result = $db->query("SELECT price FROM products WHERE id = $product_id");
    //  if ($result->num_rows > 0) {
    //      return $result->fetch_assoc()['price'];
    //  }
    //  return null; // Or handle the case where the product isn't found.
    return 10.00;  //Placeholder Price
}

// --- Example Usage (demonstration) ---

// Add some items to the cart
addToCart(1, 2); // Add 2 of product ID 1
addToCart(2, 1); // Add 1 of product ID 2
addToCart(1, 1); // Add 1 of product ID 1

// Display the cart contents
echo "<h2>Your Cart:</h2>";
echo "<ul>";
if (empty($_SESSION['cart'])) {
  echo "<li>Cart is empty.</li>";
} else {
  foreach ($_SESSION['cart'] as $product_id => $item) {
    $quantity = $item['quantity'];
    $price = $item['price'];
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . ", Price: $" . $price . "</li>";
  }
}
echo "</ul>";

// Remove an item from the cart
removeFromCart(2);

// Display the cart contents after removal
echo "<br>";
echo "<h2>Your Cart (After Removal):</h2>";
if (empty($_SESSION['cart'])) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $quantity = $item['quantity'];
        $price = $item['price'];
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . ", Price: $" . $price . "</li>";
    }
}

// Update the quantity of a product
updateQuantity(1, 3);

echo "<br>";
echo "<h2>Your Cart (After Quantity Update):</h2>";
if (empty($_SESSION['cart'])) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $quantity = $item['quantity'];
        $price = $item['price'];
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . ", Price: $" . $price . "</li>";
    }
}

?>
