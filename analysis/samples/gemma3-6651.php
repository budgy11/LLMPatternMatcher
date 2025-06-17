

<?php
session_start();

// Assuming you have a database connection established (e.g., $db)

// Function to add an item to the cart
function addToCart($product_id, $quantity, $product_name, $price) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  if (isset($_SESSION['cart'][$product_id])) {
    // Item already in cart, update quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Item not in cart, add it
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'product_name' => $product_name,
      'price' => $price
    ];
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to get the cart items
function getCartItems() {
    return $_SESSION['cart'];
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Example Usage (Simulating a request)

// Adding items to the cart
addToCart(1, 2, "Laptop", 1200);
addToCart(2, 1, "Mouse", 25);
addToCart(1, 1, "Laptop", 1200); //Adding another laptop

// Displaying the cart items
$cart_items = getCartItems();

echo "<h2>Your Shopping Cart</h2>";

if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $product_id => $item) {
        echo "<li>" . $item['product_name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";

    // Calculate total price
    $total = 0;
    foreach ($cart_items as $product_id => $item) {
        $total += $item['quantity'] * $item['price'];
    }
    echo "<p><strong>Total: $" . $total . "</strong></p>";

    //Remove Item example
    // removeFromCart(2);
    // $cart_items = getCartItems();
    // echo "<p>Updated cart items: </p>";
    // foreach ($cart_items as $product_id => $item) {
    //     echo "<li>" . $item['product_name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    // }
}

//Example update quantity
//updateQuantity(1, 3);
?>
