
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Products</h1>
  <form method="GET" action="">
    <div>
      <label for="product1">Product 1 (Price: $10):</label>
      <input type="hidden" name="product_id" value="product1">
      <input type="hidden" name="price" value="10">
      <input type="number" id="quantity1" name="quantity" value="1" min="1">
      <input type="submit" value="Add to Cart">
    </div>

    <div>
      <label for="product2">Product 2 (Price: $20):</label>
      <input type="hidden" name="product_id" value="product2">
      <input type="hidden" name="price" value="20">
      <input type="number" id="quantity2" name="quantity" value="1" min="1">
      <input type="submit" value="Add to Cart">
    </div>
  </form>

</body>
</html>


<?php
session_start();

// --- Example Product Data (Replace with your actual database or API) ---
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    3 => ['id' => 3, 'name' => 'Sneakers', 'price' => 80],
];

// --- Helper Functions ---
function addItemToCart($cartId, $productId, $quantity = 1) {
    if (isset($_SESSION['cart'][$cartId][$productId])) {
        $_SESSION['cart'][$cartId][$productId]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$cartId][$productId] = [
            'id' => $productId,
            'name' => $products[$productId]['name'],
            'price' => $products[$productId]['price'],
            'quantity' => $quantity
        ];
    }
}

function updateQuantity($cartId, $productId, $quantity) {
  if (isset($_SESSION['cart'][$cartId][$productId])) {
    $_SESSION['cart'][$cartId][$productId]['quantity'] = $quantity;
  }
}

function removeItemFromCart($cartId, $productId) {
  if (isset($_SESSION['cart'][$cartId][$productId])) {
    unset($_SESSION['cart'][$cartId][$productId]);
  }
}

function getCartTotal($cartId) {
  $total = 0;
  if (isset($_SESSION['cart'][$cartId])) {
    foreach($_SESSION['cart'][$cartId] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
  }
  return $total;
}

// --- Session Handling Functions ---
function startNewCartSession() {
  // Create a new cart session
  $_SESSION['cart'] = [];
}

function addToCart($cartId, $productId, $quantity = 1) {
  addItemToCart($cartId, $productId, $quantity);
}

function updateCartItemQuantity($cartId, $productId, $newQuantity) {
    updateQuantity($cartId, $productId, $newQuantity);
}

function removeCartItem($cartId, $productId) {
    removeItemFromCart($cartId, $productId);
}

function getCartContents($cartId) {
  return $_SESSION['cart'][$cartId] ?? []; //Return an empty array if cart doesn't exist
}

function getCartTotalAmount($cartId) {
  return getCartContents($cartId) ? getCartTotal($cartId) : 0;
}

// --- Example Usage (Demonstration) ---

// 1. Start a new cart session (or use an existing one)
startNewCartSession();

// 2. Add items to the cart
addToCart('cart1', 1); // Add one T-Shirt to cart 'cart1'
addToCart('cart1', 2, 2); // Add two Jeans to cart 'cart1'
addToCart('cart2', 1); //Add one T-Shirt to cart 'cart2'

// 3.  Update Quantity
updateCartItemQuantity('cart1', 2, 3); // Increase the quantity of Jeans in cart 'cart1' to 3

// 4.  Remove an Item
removeCartItem('cart1', 2); //Remove the Jeans (id 2) from cart 'cart1'

// 5. Get Cart Contents
$cartContents = getCartContents('cart1');
echo "Cart 1 Contents:
";
if ($cartContents) {
    foreach ($cartContents as $item) {
        echo "- " . $item['name'] . " (Quantity: " . $item['quantity'] . ", Price: $" . $item['price'] . ")
";
    }
} else {
  echo "Cart is empty.
";
}

// 6. Get Total Amount of Cart
$totalAmount = getCartTotalAmount('cart1');
echo "Total amount for cart 1: $" . $totalAmount . "
";



// --- Notes ---
// 1. Session Start:  `session_start();` MUST be called at the beginning of the script
//    to initialize the session.
// 2. Cart IDs:  I've used 'cart1', 'cart2' as example cart IDs.  In a real application,
//    you'd likely use a more robust mechanism to identify and manage user carts
//    (e.g., a unique user ID associated with the cart).
// 3.  Database/API: Replace the `$products` array with your actual product data,
//     typically retrieved from a database or an API.
// 4. Error Handling:  Add more robust error handling (e.g., checking for invalid
//     product IDs, handling database connection errors, etc.).
// 5. Security: Implement appropriate security measures, such as input validation,
//    to prevent attacks like SQL injection or cross-site scripting (XSS).
// 6. Scalability:  For a large number of users, consider using a more scalable
//     solution like Redis or Memcached to store session data.


<?php

// Start a session (if it doesn't exist)
session_start();

// Cart variables (can be stored in a database for a real application)
$cart = [];

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($cart[$product_id])) {
        $cart[$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity,
        ];
    } else {
        $cart[$product_id]['quantity'] += $quantity;
    }
}

// Function to get the cart contents
function get_cart() {
    return $cart;
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] = $quantity;
  } else {
    // Product doesn't exist in cart, so don't do anything
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    unset($cart[$product_id]);
}

// Function to clear the entire cart
function clear_cart() {
    $cart = [];
}

// --- Example Usage (demonstration) ---

// Simulate a product being added to the cart
add_to_cart(1, 'T-Shirt', 20, 2); // Product ID 1, T-Shirt, price $20, quantity 2
add_to_cart(2, 'Jeans', 50, 1);   // Product ID 2, Jeans, price $50, quantity 1

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";
}

// Example: Update quantity of item 1 to 3
update_cart_quantity(1, 3);

// Display updated cart
echo "<br><h2>Updated Cart</h2>";
echo "<ul>";
foreach ($cart as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
}
echo "</ul>";

// Example: Remove item 2 from cart
remove_from_cart(2);

echo "<br><h2>Cart After Removing Jeans</h2>";
echo "<ul>";
foreach ($cart as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
}
echo "</ul>";

// Example: Clear the cart
//clear_cart();
//echo "<br><h2>Empty Cart</h2>";
//echo "<ul>";
//if (empty($cart)) {
//    echo "<p>Your cart is empty.</p>";
//} else {
//    foreach ($cart as $product_id => $item) {
//        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
//    }
//}
//echo "</ul>";


?>
