

<?php
session_start(); // Start the session

// --- Add to Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $productId The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return bool True if the product was successfully added, false otherwise.
 */
function addToCart(int $productId, int $quantity) {
  // Check if the cart already exists.  If not, create it.
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$productId])) {
    // Product already exists, update the quantity
    $_SESSION['cart'][$productId] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$productId] = $quantity;
  }

  return true;
}


/**
 * Removes an item from the cart.
 *
 * @param int $productId The ID of the product to remove.
 * @return bool True if the product was successfully removed, false otherwise.
 */
function removeFromCart(int $productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
    return true;
  }
  return false;
}



/**
 * Gets all items in the cart.
 *
 * @return array An array of items in the cart.
 */
function getCart() {
  return $_SESSION['cart'];
}

// --- Example Usage & Demonstration ---

// 1. Adding items to the cart
addToCart(1, 2); // Add 2 of product with ID 1
addToCart(2, 1); // Add 1 of product with ID 2
addToCart(1, 3); // Add 3 more of product with ID 1


// 2. Displaying the cart contents
echo "<h2>Your Cart:</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $productId => $quantity) {
    // You'll need to fetch product details based on $productId here
    // For example:
    $product = getProductDetails($productId); // Replace with your function
    if ($product) {
      echo "<li>" . $product['name'] . " - Quantity: " . $quantity . " - Price: $" . $product['price'] . "</li>";
    } else {
      echo "<li>Product ID: " . $productId . " - Quantity: " . $quantity . " - (Product details not found)</li>";
    }

  }
  echo "</ul>";
}


// 3. Removing an item from the cart (example)
removeFromCart(2);

// 4. Displaying the cart contents again after removal
echo "<h2>Your Cart (after removal):</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $productId => $quantity) {
    // You'll need to fetch product details based on $productId here
    $product = getProductDetails($productId); // Replace with your function
    if ($product) {
      echo "<li>" . $product['name'] . " - Quantity: " . $quantity . " - Price: $" . $product['price'] . "</li>";
    } else {
      echo "<li>Product ID: " . $productId . " - Quantity: " . $quantity . " - (Product details not found)</li>";
    }
  }
  echo "</ul>";
}


// --- Helper Function (Replace with your actual product retrieval logic) ---
/**
 *  Placeholder function to simulate getting product details based on ID.
 *  In a real application, this would query your database.
 *
 * @param int $productId The product ID.
 * @return array|null  An array containing product details, or null if not found.
 */
function getProductDetails(int $productId) {
  //  Replace this with your actual product retrieval logic from a database.
  // Example:
  $products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75]
  ];

  if (isset($products[$productId])) {
    return $products[$productId];
  }
  return null;
}

?>
