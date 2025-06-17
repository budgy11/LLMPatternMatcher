

<?php
session_start();

// --- Cart Data ---
$cart = [];

// Function to add an item to the cart
function addToCart($itemId, $itemName, $itemPrice, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $cart = []; // Initialize empty cart if it doesn't exist
  }

  $cart[] = [
    'id' => $itemId,
    'name' => $itemName,
    'price' => $itemPrice,
    'quantity' => $quantity
  ];

  $_SESSION['cart'] = $cart;
}

// Function to update quantity of an item in cart
function updateQuantity($itemId, $newQuantity) {
    if (empty($_SESSION['cart'])) {
        return false; // Cart is empty, cannot update
    }

    foreach ($cart as &$item) { //Use reference (&) to modify original array
        if ($item['id'] == $itemId) {
            $item['quantity'] = $newQuantity;
            return true;
        }
    }
    return false; //Item not found
}

// Function to remove an item from the cart
function removeItem($itemId) {
    if (empty($_SESSION['cart'])) {
        return false; // Cart is empty, cannot remove
    }

    $originalCart = $_SESSION['cart']; //Store the original cart for comparison
    $cart = array_filter($_SESSION['cart'], function ($key) use ($itemId) {
        return $_SESSION['cart'][$key]['id'] !== $itemId;
    });
    
    //Check if cart is empty after filtering
    if (empty($cart)) {
      $_SESSION['cart'] = []; //Ensure cart is empty if all items are removed.
    }

    return true;
}

// Function to display the cart contents
function displayCart() {
  echo "<h2>Your Cart</h2>";
  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";

  // Display total price
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += ($item['price'] * $item['quantity']);
  }
  echo "<p><strong>Total: $" . $total . "</strong></p>";
}


// --- Example Usage (Simulating some actions) ---

// Add some items to the cart
addToCart(1, "T-Shirt", 20.00, 2);
addToCart(2, "Jeans", 50.00, 1);
addToCart(3, "Hat", 15.00);

//Update quantity of an item
updateQuantity(1, 3); // Change quantity of T-Shirt to 3

// Display the cart
displayCart();

// Remove an item
removeItem(2);
displayCart();


// --- End Example Usage ---
?>
