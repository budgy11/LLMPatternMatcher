

<?php

session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increase quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}


// Example Usage (Simulated product information - replace with your database or API)
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75],
];


// Example: Adding items to the cart
addToCart(1, $products[1]['name'], $products[1]['price']);
addToCart(2, $products[2]['name'], $products[2]['price'], 3);
addToCart(3, $products[3]['name'], $products[3]['price']);


// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_data) {
    echo "<li>";
    echo "<strong>" . $product_data['name'] . "</strong> - $" . $product_data['price'] . " (Quantity: " . $product_data['quantity'] . ")";
    echo "<form action='' method='post'>"; // Empty action to prevent accidental form submission
    echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
    echo "<input type='number' name='quantity' value='" . $product_data['quantity'] . "' min='1'>";
    echo "<button type='submit'>Update</button>";
    echo "</form>";
    echo "</li>";
  }
  echo "</ul>";
}


// Example: Handling the update form submission (if you want to update quantities)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        $new_quantity = intval($_POST['quantity']);  // Ensure the quantity is an integer

        if (isset($_SESSION['cart'][$product_id])) {
            updateQuantity($product_id, $new_quantity);
        } else {
            echo "<p>Product not found in cart.</p>";
        }
    }
}



?>
