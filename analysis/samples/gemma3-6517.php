

<?php

session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  $_SESSION['cart'][$product_id] = [
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  ];
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart items
function getCartItems() {
  return $_SESSION['cart'];
}

// Function to calculate the total cart value
function calculateTotal() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
  }
  return $total;
}

// --- Example Usage (Demonstration) ---

// 1. Add an item to the cart
addToCart(1, "Awesome T-Shirt", 20, 2);
addToCart(2, "Cool Mug", 10, 3);

// 2. Display the cart items
echo "<h2>Your Cart</h2>";
if (empty(getCartItems())) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach (getCartItems() as $product_id => $item) {
    echo "<li>";
    echo "Product: " . $item['name'] . "<br>";
    echo "Price: $" . $item['price'] . "<br>";
    echo "Quantity: " . $item['quantity'] . "<br>";
    echo "Total for this item: $" . ($item['price'] * $item['quantity']) . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

// 3. Update the quantity of a product
updateCartQuantity(1, 5);  // Change quantity of Awesome T-Shirt to 5

// 4. Calculate and display the total
$total = calculateTotal();
echo "<p><strong>Total Cart Value: $" . $total . "</strong></p>";


// 5. Remove an item from the cart (Example)
//removeCartItem(2); //Uncomment this line to remove the cool mug.

?>
