

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize empty cart array
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}


// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}


// Function to remove an item from the cart
function removeItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}


// *** Example Usage (Simulated Input - Replace with Actual Product Data) ***

// Add an item to the cart
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);
addToCart(1, "Laptop", 1200, 3); // Increase quantity of item 1

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty(getCartContents())) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach (getCartContents() as $product_id => $product) {
        echo "<li>" . $product['name'] . " - $" . $product['price'] . " x " . $product['quantity'] . " = $" . ($product['price'] * $product['quantity']) . "</li>";
    }
    echo "</ul>";
}



// Example of updating quantity
updateQuantity(2, 5); // Increase quantity of Mouse to 5
echo "<br>";
echo "<h2>Your Shopping Cart (After Update)</h2>";
if (empty(getCartContents())) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach (getCartContents() as $product_id => $product) {
        echo "<li>" . $product['name'] . " - $" . $product['price'] . " x " . $product['quantity'] . " = $" . ($product['price'] * $product['quantity']) . "</li>";
    }
    echo "</ul>";
}


// Example of removing an item
removeItem(2);
echo "<br>";
echo "<h2>Your Shopping Cart (After Removal)</h2>";
if (empty(getCartContents())) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach (getCartContents() as $product_id => $product) {
        echo "<li>" . $product['name'] . " - $" . $product['price'] . " x " . $product['quantity'] . " = $" . ($product['price'] * $product['quantity']) . "</li>";
    }
    echo "</ul>";
}

?>
