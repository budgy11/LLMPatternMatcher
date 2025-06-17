

<?php
session_start();

// Function to add an item to the cart
function add_to_cart($product_id, $quantity) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = array($product_id => $quantity);
  } else {
    // Check if the product already exists in the cart
    if (isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id] += $quantity;
    } else {
      // If the product is not in the cart, add it
      $_SESSION['cart'][$product_id] = $quantity;
    }
  }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Function to get the contents of the cart
function get_cart_contents() {
  return $_SESSION['cart'];
}

// --- Example Usage ---

// 1. Adding items to the cart
add_to_cart(1, 2); // Add product with ID 1 to cart with quantity 2
add_to_cart(2, 1); // Add product with ID 2 to cart with quantity 1
add_to_cart(1, 3); // Add more of product with ID 1

// 2. Viewing the cart
$cart_items = get_cart_contents();
echo "<h2>Your Cart</h2>";
if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $product_id => $quantity) {
    //  You'll need to have a way to retrieve product details based on $product_id
    //  This is just a placeholder - replace with your actual product retrieval logic
    $product_name = get_product_name($product_id);  // Example function - see below
    echo "<li>Product: $product_name (Quantity: $quantity)</li>";
  }
  echo "</ul>";
}

// 3. Removing an item
//remove_from_cart(2);

// 4. Updating quantity
// update_cart_quantity(1, 5);


// --- Placeholder functions for product details (replace with your database/logic) ---

function get_product_name($product_id) {
    // Replace this with your actual product retrieval logic
    // This is just an example -  you'll likely fetch from a database
    switch ($product_id) {
        case 1: return "Awesome T-Shirt";
        case 2: return "Cool Mug";
        default: return "Unknown Product";
    }
}
?>
