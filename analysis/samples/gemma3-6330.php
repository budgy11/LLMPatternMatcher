

<?php
session_start();

// Example Product Information (Replace with your actual product data)
$product_id = 1;
$product_name = "Awesome T-Shirt";
$product_price = 25.00;

// Function to add a product to the cart
function addToCart($product_id, $product_name, $product_price) {
  // Check if the cart already exists in the session
  if (isset($_SESSION['cart'])) {
    // If the cart exists, check if the product is already in it
    if (isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id]['quantity']++;
    } else {
      // If the product is not in the cart, add it with a quantity of 1
      $_SESSION['cart'][$product_id] = [
        'id' => $product_id,
        'name' => $product_name,
        'price' => $product_price,
        'quantity' => 1
      ];
    }
  } else {
    // If the cart doesn't exist, create it with the new product
    $_SESSION['cart'] = [
      $product_id => [
        'id' => $product_id,
        'name' => $product_name,
        'price' => $product_price,
        'quantity' => 1
      ]
    ];
  }
}

// Example: Add the product to the cart
addToCart($product_id, $product_name, $product_price);

// Display the cart content
echo "<h2>Your Shopping Cart</h2>";

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item_id => $item_data) {
    echo "<li>";
    echo "<strong>" . $item_data['name'] . "</strong> - $" . $item_data['price'] . " x " . $item_data['quantity'] . " = $" . ($item_data['price'] * $item_data['quantity']) . "</li>";
  }
  echo "</ul>";

  // Calculate the total amount
  $total_amount = 0;
  foreach ($_SESSION['cart'] as $item_id => $item_data) {
    $total_amount += ($item_data['price'] * $item_data['quantity']);
  }
  echo "<p><strong>Total: $" . $total_amount . "</strong></p>";
} else {
  echo "<p>Your cart is empty.</p>";
}

// Example: Increment the quantity of the product in the cart
if (isset($_GET['action']) && $_GET['action'] == 'increment') {
  $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : null;

  if ($product_id !== null) {
    if (isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id]['quantity']++;
    }
  }
}

// Example: Decrement the quantity of the product in the cart
if (isset($_GET['action']) && $_GET['action'] == 'decrement') {
  $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : null;

  if ($product_id !== null) {
    if (isset($_SESSION['cart'][$product_id])) {
      if ($_SESSION['cart'][$product_id]['quantity'] > 1) {
        $_SESSION['cart'][$product_id]['quantity']--;
      } else {
        // Remove the item if quantity is 1 or less
        unset($_SESSION['cart'][$product_id]);
      }
    }
  }
}

// Example: Remove an item from the cart
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
  $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : null;

  if ($product_id !== null) {
    if (isset($_SESSION['cart'][$product_id])) {
      unset($_SESSION['cart'][$product_id]);
    }
  }
}

?>
