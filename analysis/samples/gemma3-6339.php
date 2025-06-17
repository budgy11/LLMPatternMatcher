

<?php

// This example demonstrates a simple cart session implementation.
// It's a basic illustration and needs to be adapted for real-world use, 
// particularly with database integration and security best practices.

session_start(); // Start the session

// Example product data (replace with your actual product database)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75]
];

// Function to add an item to the cart
function addToCart($productId, $quantity = 1)
{
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'][$productId] = $_SESSION['cart'][$productId] ?? 0;  // Initialize if not present
        $_SESSION['cart'][$productId] = $_SESSION['cart'][$productId] + $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

// Function to remove an item from the cart
function removeFromCart($productId)
{
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}


// Function to update the quantity of an item in the cart
function updateQuantity($productId, $quantity) {
  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId] = $quantity;
  }
}


// Handle adding items to the cart
if (isset($_POST['add_to_cart'])) {
    $productId = (int)$_POST['product_id']; // Sanitize input

    if (isset($products[$productId])) {
        addToCart($productId);
    }
}

// Handle removing items from the cart
if (isset($_POST['remove_from_cart'])) {
    $productId = (int)$_POST['product_id']; // Sanitize input
    removeFromCart($productId);
}


// Handle updating quantity
if (isset($_POST['update_quantity'])) {
  $productId = (int)$_POST['product_id'];
  $quantity = (int)$_POST['quantity'];
  updateQuantity($productId, $quantity);
}


// Display the cart contents
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $quantity) {
        $cart_count++;
        $product = $products[$id];
        $total_price = $product['price'] * $quantity;
        echo '<p><strong>Item:</strong> ' . $product['name'] . '<br>';
        echo '<strong>Quantity:</strong> ' . $quantity . '<br>';
        echo '<strong>Price:</strong> $' . $product['price'] . '<br>';
        echo '<strong>Total:</strong> $' . $total_price . '</p>';
    }
}

echo '<p><strong>Total Items in Cart:</strong> ' . $cart_count . '</p>';

// Example:  A button to clear the entire cart
if (isset($_POST['clear_cart'])) {
    session_destroy();
    echo "<p>Cart cleared.</p>";
}

?>
