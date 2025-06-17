

<?php
session_start(); // Start the session

// Function to add an item to the cart
function addToCart($product_id, $quantity, $product_name) {
  // Check if the cart exists in the session
  if (isset($_SESSION['cart'])) {
    // If it exists, check if the product already exists in the cart
    if (isset($_SESSION['cart'][$product_id])) {
      // Product exists, update the quantity
      $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
      // Product doesn't exist in the cart, add it
      $_SESSION['cart'][$product_id] = array(
        'name' => $product_name,
        'quantity' => $quantity
      );
    }
  } else {
    // Cart doesn't exist, create it with the first item
    $_SESSION['cart'] = array($product_id => array('name' => $product_name, 'quantity' => $quantity));
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Example Usage:

// Add some items to the cart
addToCart(1, 2, 'T-Shirt');
addToCart(2, 1, 'Jeans');
addToCart(1, 3, 'T-Shirt'); // Adding more of the first item

// Get the cart contents
$cart = getCartContents();
echo "Cart Contents: <br>";
print_r($cart); // Display the cart contents (for debugging)

// Remove an item from the cart
removeFromCart(2);
echo "<br>Cart after removing Jeans: <br>";
print_r($cart);

// Update the quantity of a product
updateCartQuantity(1, 5);
echo "<br>Cart after updating T-Shirt quantity: <br>";
print_r($cart);


// Example to display the cart contents in a more user-friendly way (HTML)
echo "<br><h2>Shopping Cart:</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}

?>
