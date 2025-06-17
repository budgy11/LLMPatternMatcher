

<?php
session_start();

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
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

// Function to update the quantity of a product in the cart
function update_cart_quantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}


// Function to remove a product from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}


// Function to get the cart contents
function get_cart_contents() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  }
  return [];
}

// ------------------ Example Usage ------------------

// Example 1: Add a product to the cart
add_to_cart(1, 'T-Shirt', 20, 2);
add_to_cart(2, 'Jeans', 50, 1);

// Example 2: Update the quantity of a product
update_cart_quantity(1, 3); // Increase quantity of T-Shirt to 3

// Example 3: Remove a product from the cart
remove_from_cart(2);

// Example 4: Get the cart contents
$cart_contents = get_cart_contents();
echo "<h2>Cart Contents:</h2>";
if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $product_id => $product_data) {
    echo "<li>" . $product_data['name'] . " - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
  }
  echo "</ul>";
}

?>
