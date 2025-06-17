

<?php
session_start(); // Start the session

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart exists in the session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];  // Initialize the cart array if it doesn't exist
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add the product to the cart
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
  return $_SESSION['cart'] ?? [];  // Returns the cart array or an empty array if it doesn't exist
}

// Example Usage (Simulated Product Data)
$products = [
  1 => ['name' => 'Laptop', 'price' => 1200],
  2 => ['name' => 'Mouse', 'price' => 25],
  3 => ['name' => 'Keyboard', 'price' => 75]
];

// ---  Handling Add to Cart Request (Simulated) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
  $product_id = (int)$_POST['product_id']; // Ensure it's an integer
  $quantity = (int)$_POST['quantity'] ?? 1; // Default quantity is 1

  if (isset($products[$product_id])) {
    addToCart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
    echo "Item added to cart! (Product ID: " . $product_id . ")";
  } else {
    echo "Product not found.";
  }
}


// --- Displaying the Cart (for demonstration purposes) ---
if (isset($_SESSION['cart'])) {
  echo "<h2>Your Shopping Cart</h2>";
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    $product_name = $item['name'];
    $product_price = $item['price'];
    $quantity = $item['quantity'];

    echo "<li>" . $product_name . " - $" . $product_price . " x " . $quantity . " = $" . ($product_price * $quantity) . "</li>";
  }
  echo "</ul>";

  //  Example: Update quantity (simulated)
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product_id']) && isset($_POST['new_quantity'])) {
    $product_id = (int)$_POST['update_product_id'];
    $new_quantity = (int)$_POST['new_quantity'];
    updateQuantity($product_id, $new_quantity);
    echo "<p>Quantity updated to: " . $new_quantity . "</p>";
  }

  // Example: Remove item from cart (simulated)
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_product_id'])) {
    $product_id = (int)$_POST['remove_product_id'];
    removeItem($product_id);
    echo "<p>Item removed from cart.</p>";
  }


} else {
  echo "<p>Your cart is empty.</p>";
}
?>
