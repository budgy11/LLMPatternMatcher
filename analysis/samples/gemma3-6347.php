

<?php
session_start();

// --- Cart Operations ---

// 1. Add Item to Cart
function add_to_cart($product_id, $quantity = 1, $product_name = "") {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'name' => $product_name // Optional: Add product name for display
    ];
  }
}

// 2. Remove Item from Cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// 3. Update Item Quantity in Cart
function update_cart_quantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}


// 4. Get Cart Contents
function get_cart_contents() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  } else {
    return []; // Return an empty array if cart is empty
  }
}


// --- Example Usage (Simulated Product Data - Replace with your actual database/product data) ---

$products = [
  1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
  2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
  3 => ['id' => 3, 'name' => 'Shoes', 'price' => 80],
];


// --- Handle Actions (Simulated - Replace with form handling from a web form) ---

// Example 1: Add a T-Shirt to the cart
if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  add_to_cart($product_id, $quantity);
  // You would typically display a success message here.
}

// Example 2: Remove Jeans
if (isset($_POST['action']) && $_POST['action'] == 'remove_item') {
    $product_id = $_POST['product_id'];
    remove_from_cart($product_id);
    // You would typically display a success message here.
}

// Example 3: Update quantity of a T-Shirt
if (isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    update_cart_quantity($product_id, $new_quantity);
}


// --- Display Cart Contents (For demonstration purposes) ---
$cart_contents = get_cart_contents();

echo "<h2>Your Shopping Cart</h2>";

if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $product_id => $product_data) {
    $product_name = $products[$product_id]['name'];
    $product_price = $products[$product_id]['price'];

    echo "<li>" . $product_name . " - $" . $product_price . " (Quantity: " . $product_data['quantity'] . ")</li>";
  }
  echo "</ul>";
}

?>
