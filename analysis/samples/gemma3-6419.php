

<?php
session_start();

// 1.  Handle Adding Items to the Cart
function addItemToCart($productId, $quantity = 1, $productName = null) {
  // Check if the cart exists in the session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$productId])) {
    // Product exists, increment quantity
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$productId] = array(
      'quantity' => $quantity,
      'name' => $productName ?? $productId, // Use productName if available, otherwise use product ID
    );
  }
}

// Example: Adding a product to the cart
addItemToCart(123, 2); // Add product with ID 123, quantity 2
addItemToCart(456, 1, 'Awesome T-Shirt'); // Add product with ID 456, quantity 1, product name 'Awesome T-Shirt'


// 2. Handle Removing Items from the Cart
function removeItemFromCart($productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}

// Example: Remove an item from the cart
removeItemFromCart(123);

// 3. Handle Updating Quantity of an Item in the Cart
function updateQuantity($productId, $newQuantity) {
  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
  }
}

// Example: Update the quantity of product with ID 456 to 3
updateQuantity(456, 3);



// 4.  Display the Cart Contents (for demonstration)
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $id => $item) {
    echo "<li>";
    echo "Product ID: " . $id . "<br>";
    echo "Product Name: " . $item['name'] . "<br>";
    echo "Quantity: " . $item['quantity'] . "<br>";
    echo "Subtotal: $" . $item['quantity'] * 10  . "<br>"; // Assuming price is $10 per item
    echo "</li>";
  }
  echo "</ul>";
}

// 5. Session Management - Important Considerations:
//    - Session starts:  session_start() must be called at the beginning of each script
//    - Session Destroy:  You should destroy the session when the user logs out or leaves the site.
//      Example: session_destroy();
?>
