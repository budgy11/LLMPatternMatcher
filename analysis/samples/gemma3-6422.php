

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $quantity, $product_name = 'Default Product') {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart as an empty array
  }

  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'name' => $product_name // Add product name for display
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
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to display the cart contents
function displayCart() {
  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Shopping Cart</h2>";
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item_id => $item_data) {
    $product_name = isset($item_data['name']) ? $item_data['name'] : "Product " . $item_id; // Use name if set, else "Product" + ID
    echo "<li>" . $product_name . " - Quantity: " . $item_data['quantity'] . " - Price: $10 (Example)</li>"; // Replace $10 with actual price
  }
  echo "</ul>";
}


// --- Example Usage (Simulating adding to cart on a button click) ---

// If a product was added to the cart (e.g., by clicking a button)
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];

  addToCart($product_id, $quantity);
}

// If a product was removed from the cart
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  removeFromCart($product_id);
}

// If the quantity of an item was updated
if (isset($_POST['update_quantity'])) {
  $product_id = $_POST['product_id'];
  $new_quantity = $_POST['quantity'];
  updateQuantity($product_id, $new_quantity);
}

// --- Display the cart ---
displayCart();

// Example HTML for a product to add to the cart (replace with your actual product details)
?>
