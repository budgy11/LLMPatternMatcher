

<?php
session_start();

// Function to add items to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart exists, initialize if not
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
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

// Function to calculate the cart total
function calculateCartTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    return $total;
}

// --- Example Usage ---

// 1. Add an item to the cart
addToCart(1, "T-Shirt", 20, 2); // Product ID 1, T-Shirt, $20, Quantity 2
addToCart(2, "Jeans", 50, 1); // Product ID 2, Jeans, $50, Quantity 1


// 2. Update the quantity of an item
updateCartQuantity(1, 3); // Increase the quantity of T-Shirts to 3

// 3. Remove an item
//removeCartItem(2); // Remove Jeans


// 4. Get the cart contents
$cart = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $item) {
        echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price per item: $" . $item['price'] . " - Total: $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";
}


// 5. Calculate the total
$total = calculateCartTotal();
echo "<p><strong>Total Cart Value: $" . $total . "</strong></p>";

?>
