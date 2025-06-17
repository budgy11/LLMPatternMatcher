

<?php
session_start();

// --- Cart Functionality ---

// Add item to cart
function add_to_cart($product_id, $quantity) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = array($product_id => $quantity);
  } else {
    if (isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id] += $quantity;
    } else {
      $_SESSION['cart'][$product_id] = $quantity;
    }
  }
}

// Remove item from cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Update quantity of an item in cart
function update_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Get cart contents
function get_cart_contents() {
  return $_SESSION['cart'];
}


// --- Example Usage ---

// 1.  Let's say we have a product with ID 1
$product_id = 1;

// 2.  Add an item to the cart (quantity of 2)
add_to_cart($product_id, 2);

// 3.  Add another item to the cart (quantity of 1)
add_to_cart($product_id, 1);


// 4.  Display the cart contents
echo "<h2>Your Cart</h2>";
echo "<ul>";

$cart_items = get_cart_contents();
if (!empty($cart_items)) {
  foreach ($cart_items as $product_id => $quantity) {
    //  In a real application, you'd likely fetch product details
    //  based on the $product_id.  This is just a placeholder.

    // Simulate getting product details
    $product_name = "Product " . $product_id;
    $product_price = 25.00;

    echo "<li>" . $product_name . " - Quantity: " . $quantity . " - Price: $" . $product_price . "</li>";
  }
} else {
  echo "<li>Your cart is empty.</li>";
}

echo "</ul>";

// --- Remove item example ---
// remove_from_cart($product_id);

// --- Update quantity example ---
// update_quantity($product_id, 5);  // Change quantity of product 1 to 5
?>
