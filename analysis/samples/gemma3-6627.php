

<?php

session_start();

// --- Cart Data (In a real application, this would likely come from a database) ---
$cart = [];

// Helper function to add items to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
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

// Helper function to remove an item from the cart
function removeFromCart($product_id) {
    unset($cart[$product_id]);
}

// Helper function to update the quantity of an item in the cart
function updateQuantity($product_id, $newQuantity) {
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $newQuantity;
    }
}

// --- Example Product Data (Replace with your actual data) ---
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75],
];

// --- User Cart Session Management ---

// Add an item to the cart when a user adds an item (e.g., from a product page)
if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
  $product_id = $_POST['product_id'];
  $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; // Default to 1 if quantity is not provided

  if (isset($products[$product_id])) {
    addToCart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
  } else {
    // Handle the case where the product ID is not found
    echo "Product ID " . $product_id . " not found.";
  }
}

// Remove an item from the cart
if (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
    $product_id = $_POST['product_id'];
    removeFromCart($product_id);
}

// Update quantity of an item
if (isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
    $product_id = $_POST['product_id'];
    $newQuantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;
    updateQuantity($product_id, $newQuantity);
}


// --- Display the Cart Contents ---

echo "<h2>Your Shopping Cart</h2>";

if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " (Quantity: " . $item['quantity'] . ")</li>";
  }
  echo "</ul>";

  // Calculate the total cost
  $total = 0;
  foreach ($cart as $product_id => $item) {
    $total += $item['price'] * $item['quantity'];
  }
  echo "<p><strong>Total: $" . $total . "</strong></p>";
}

?>
