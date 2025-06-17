

<?php
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity) {
  // Add the item to the cart array
  $_SESSION['cart'][] = [
    'id' => $product_id,
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  ];

  // Optional:  Update quantity if item already exists
  updateCartQuantity($product_id, $quantity);
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
  // Iterate through the cart array
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
      $item['quantity'] = $quantity;
      return; // Exit the loop once the item is updated
    }
  }
}

// Function to remove an item from the cart
function removeItemFromCart($product_id) {
  foreach ($_SESSION['cart'] as $key => $item) {
    if ($item['id'] == $product_id) {
      unset($_SESSION['cart'][$key]);
      // Re-index the array after removing an element
      $_SESSION['cart'] = array_values($_SESSION['cart']);
      return;
    }
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// --- Example Usage (Illustrative - You'd likely integrate this into a larger application) ---

// 1. Add an item to the cart
// $productId = 123;
// $productName = "Awesome T-Shirt";
// $price = 25.00;
// $quantity = 2;
// addToCart($productId, $productName, $price, $quantity);

// 2. Display the cart contents
// $cartContents = getCartContents();
// if (!empty($cartContents)) {
//   echo "<h2>Your Shopping Cart</h2>";
//   echo "<ul>";
//   foreach ($cartContents as $item) {
//     echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
//   }
//   echo "</ul>";
// } else {
//   echo "<p>Your cart is empty.</p>";
// }

// 3. Remove an item from the cart
// removeItemFromCart(123);

// 4. Get Cart Contents again after removing
// $cartContents = getCartContents();
// if (!empty($cartContents)) {
//   echo "<h2>Your Shopping Cart</h2>";
//   echo "<ul>";
//   foreach ($cartContents as $item) {
//     echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
//   }
//   echo "</ul>";
// } else {
//   echo "<p>Your cart is empty.</p>";
// }


?>
