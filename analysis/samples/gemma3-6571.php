

<?php

session_start(); // Start the session

// Check if the cart is empty or not
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = []; // Initialize the cart array if it doesn't exist
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product already exists, increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product not in cart, add it with quantity 1
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    } else {
        // Product not in cart, return an error or do nothing - depends on your design
        // For simplicity, just return a message
        return "Product not found in cart.";
    }
}


// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}


// Example Usage (simulated form submission)
// In a real application, this would be handled by an actual form

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = intval($_POST['quantity']); // Make sure quantity is an integer

    addToCart($product_id, "Product " . $product_id, 10.00, $quantity); //Example
  }

  if (isset($_POST['remove_product_id'])) {
    $remove_product_id = $_POST['remove_product_id'];
    removeCartItem($remove_product_id);
  }

   if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = intval($_POST['quantity']);
    updateCartQuantity($product_id, $quantity);
  }
}



// Display the cart contents
$cart = getCartContents();

echo "<h2>Your Shopping Cart</h2>";

if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_data) {
    echo "<li>";
    echo "<strong>Product Name:</strong> " . $product_data['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_data['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_data['quantity'] . "<br>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
    echo "<input type='submit' value='Remove from Cart'>";
    echo "</form>";
    echo "</li>";
  }
  echo "</ul>";
}

?>
